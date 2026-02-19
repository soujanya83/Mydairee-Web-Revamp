<?php

namespace App\Services\Firebase;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Exception;

class FirebaseNotificationService
{
    /**
     * @var FirebaseAuthService
     */
    protected FirebaseAuthService $authService;

    /**
     * Inject FirebaseAuthService via constructor.
     */
    public function __construct(FirebaseAuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Send a push notification to a device token via Firebase Cloud Messaging.
     *
     * @param string $token
     * @param string $title
     * @param string $body
     * @param array $data
     * @return JsonResponse
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): JsonResponse
    {
        try {
            // 2. Get access token from FirebaseAuthService
            $accessToken = $this->authService->getAccessToken();
            if (!$accessToken) {
                throw new Exception('Unable to obtain Firebase access token.');
            }

            // 3. Get project_id from config
            $projectId = config('services.firebase.project_id');
            if (!$projectId) {
                throw new Exception('Firebase project_id not configured.');
            }

            // 5. Build payload for FCM
            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data,
                ],
            ];

            // 4. Set headers
            $headers = [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ];

            // 3. Compose FCM endpoint URL
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            // 6. Send POST request to FCM
            $response = Http::withHeaders($headers)->post($url, $payload);

            // 8. Return JSON response
            if ($response->ok()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification sent successfully.',
                    'fcm_response' => $response->json(),
                ]);
            } else {
                // 7. Log error if failed
                Log::error('FCM notification failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send notification.',
                    'fcm_response' => $response->json(),
                ], $response->status());
            }
        } catch (Exception $e) {
            // 7. Log unexpected errors
            Log::error('FirebaseNotificationService error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
            ], 500);
        }
    }
}
