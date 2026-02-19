<?php

namespace App\Services\Firebase;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class FirebaseAuthService
{
    /**
     * Get Firebase OAuth2 access token for FCM.
     *
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        try {
            // 1. Load Firebase service account credentials from config
           $credentialsPath = config('services.firebase.credentials');

            $fullPath = base_path($credentialsPath);

            if (!file_exists($fullPath)) {
                dd('Credentials file not found at: ' . $fullPath);
            }

            $credentials = json_decode(file_get_contents($fullPath), true);

            if (!is_array($credentials)) {
                throw new Exception('Invalid Firebase credentials configuration.');
            }

            // 2. Extract required fields
            $privateKey = $credentials['private_key'] ?? null;

            if ($privateKey) {
                $privateKey = str_replace('\n', "\n", $privateKey);
            }

            $clientEmail = $credentials['client_email'] ?? null;
            $tokenUri = $credentials['token_uri'] ?? null;
            if (!$privateKey || !$clientEmail || !$tokenUri) {
                throw new Exception('Missing required Firebase credential fields.');
            }

            // 3. Build JWT header and claim set
            $now = time();
            $header = [
                'alg' => 'RS256',
                'typ' => 'JWT',
            ];
            $claims = [
                'iss' => $clientEmail,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => $tokenUri,
                'iat' => $now,
                'exp' => $now + 3600,
            ];

            // 4. Base64url encode header and claim set
            $base64UrlHeader = $this->base64UrlEncode(json_encode($header));
            $base64UrlClaims = $this->base64UrlEncode(json_encode($claims));
            $unsignedJwt = $base64UrlHeader . '.' . $base64UrlClaims;

            // 5. Sign using openssl_sign with private key (SHA256)
            $signature = '';
            $success = openssl_sign($unsignedJwt, $signature, $privateKey, 'SHA256');
            if (!$success) {
                throw new Exception('Failed to sign JWT with private key.');
            }
            $base64UrlSignature = $this->base64UrlEncode($signature);

            // 6. Combine to form JWT
            $jwt = $unsignedJwt . '.' . $base64UrlSignature;

            // 7. Send POST request to Google OAuth2 token endpoint
            // $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            //     'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            //     'assertion' => $jwt,
            // ]);
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]);

                // dd([
                //     'status' => $response->status(),
                //     'body' => $response->body()
                // ]);


            if (!$response->ok()) {
                // Log::error('Firebase OAuth2 token request failed', [
                //     'status' => $response->status(),
                //     'body' => $response->body(),
                // ]);
                return null;
            }

            $data = $response->json();
            return $data['access_token'] ?? null;
        } catch (Exception $e) {
            // 9. Log unexpected errors
            Log::error('FirebaseAuthService error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return null;
        }
    }
//     public function getAccessToken(): ?string
// {
//     try {

//         $credentialsPath = base_path(config('services.firebase.credentials'));

//         if (!file_exists($credentialsPath)) {
//             dd('Credentials file NOT found', $credentialsPath);
//         }

//         $credentials = json_decode(file_get_contents($credentialsPath), true);

//         dd([
//             'private_key_exists' => isset($credentials['private_key']),
//             'client_email_exists' => isset($credentials['client_email']),
//             'token_uri_exists' => isset($credentials['token_uri']),
//         ]);

//     } catch (\Exception $e) {
//         dd('EXCEPTION', $e->getMessage());
//     }
// }


    /**
     * Base64Url encode helper (RFC 7515)
     *
     * @param string $data
     * @return string
     */
    public function base64UrlEncode(string $data): string
    {
        // 10. Helper for base64url encoding
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
