<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Center;
use App\Models\Usercenter;
use App\Models\Child;
use App\Models\Childparent;
use App\Models\DevMilestone;
use App\Models\DevMilestoneMain;
use App\Models\DevMilestoneSub;
use App\Models\EYLFActivity;
use App\Models\EYLFOutcome;
use App\Models\EYLFSubActivity;
use App\Models\MontessoriActivity;
use App\Models\MontessoriSubActivity;
use App\Models\MontessoriSubject;
use App\Models\Observation;
use App\Models\ObservationChild;
use App\Models\ObservationComment;
use App\Models\ObservationDevMilestoneSub;
use App\Models\ObservationEYLF;
use App\Models\ObservationLink;
use App\Models\ObservationMedia;
use App\Models\ObservationStaff;
use App\Models\Reflection;
use App\Models\SnapshotMedia;
use App\Models\SnapshotChild;
use App\Models\Snapshot;
use App\Models\ObservationMontessori;
use App\Models\Permission;
use App\Models\ProgramPlanTemplateDetailsAdd;
use App\Models\Userprogressplan;
use App\Models\Room;
use App\Models\RoomStaff;
use App\Models\SeenObservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Notifications\ObservationAdded;
use Illuminate\Support\Facades\Mail;

class ObservationsController extends Controller
{

// public function TranslateObservation(Request $request)
// {
//     $reflection  = $request->reflection;
//     $observation = $request->observation;
//     $childvoice  = $request->childvoice;
//     $futureplan  = $request->futureplan;
//     $analysis    = $request->analysis;
//     $language    = $request->language;
//     $eylf = $request->eylf;
//     $development_milestone = $request->development_milestone;
//     $montessori_assesment = $request->montessori_assesment;

//     // Combine all fields into one text for translation
//     $texts = [
//         'reflection' => $reflection,
//         'observation' => $observation,
//         'childvoice' => $childvoice,
//         'futureplan' => $futureplan,
//         'analysis' => $analysis,
//         'eylf' => $eylf,
//         'development_milestone' => $development_milestone,
//         'montessori_assesment' => $montessori_assesment
//     ];

//     $translatedData = [];

//     foreach($texts as $key => $text) {
//         if (!empty($text)) {
//             $translatedData[$key] = $this->translateApi($text, $language);
//         } else {
//             $translatedData[$key] = ''; // or null, depending on your preference
//         }
//     }

//     return response()->json([
//         'status' => true,
//         'message' => 'translated successfully',
//         'data' => $translatedData
//     ]);
// }



// public function translateApi($text, $language)
// {
//      $apiKey = 'sk-d1febdfb38e3491391e5ca4ce911be5c';

//     $prompt = "You are a professional translator. 
//     Translate the following text into {$language}. 
//     Keep the tone natural, fluent, and professional. 
//     Maintain the original structure (headings, bullet points, etc.). 
//     Return only the translated version, without any explanations.

//     Text:
//     {$text}";

//     $response = Http::withHeaders([
//         'Authorization' => "Bearer $apiKey",
//         'Content-Type'  => 'application/json',
//     ])->timeout(60)
//         ->retry(3, 2000)->post('https://api.deepseek.com/chat/completions', [
//         "model"    => "deepseek-chat",
//         "messages" => [
//             ["role" => "system", "content" => "You are a professional translator."],
//             ["role" => "user", "content" => $prompt]
//         ]
//     ]);

//     $json = $response->json();
//     return $json['choices'][0]['message']['content'] ?? $text;
// }



// private function translateApi($fields, $targetLanguage)
// {

//      ini_set('max_execution_time', 300); // 5 minutes
//     ini_set('default_socket_timeout', 300); 

//     $apiKey = 'sk-d1febdfb38e3491391e5ca4ce911be5c';
   

//     $prompt = "Translate the following sections to {$targetLanguage},  including the text inside parentheses
//     Maintain the section labels (Reflection, Observation, etc.) in English.
//     Translate only the content after each label.
//     Keep the structure exactly the same.

//     Text:
//     {$fields}

//     Translated version:";

//     $response = Http::withHeaders([
//         'Authorization' => "Bearer {$apiKey}",
//         'Content-Type' => 'application/json',
//     ])
//      ->timeout(120) // Increased from 60 to 120 seconds
//         ->connectTimeout(120) // Connection timeout
//         ->retry(3, 2000, function ($exception, $request) {
//             // Retry only on timeout or connection errors
//             return $exception instanceof \Illuminate\Http\Client\ConnectionException;
//         })
//     ->post('https://api.deepseek.com/chat/completions', [
//         "model" => "deepseek-chat",
//         "messages" => [
//             [
//                 "role" => "system", 
//                 "content" => "You translate text while preserving section labels and structure."
//             ],
//             [
//                 "role" => "user", 
//                 "content" => $prompt
//             ]
//         ],
//         "temperature" => 1.3
//     ]);

//     $data = $response->json();
    
//     return $data['choices'][0]['message']['content'];
// }


// public function TranslateObservation(Request $request)
// {

//           ini_set('max_execution_time', 300); // 5 minutes
//     ini_set('default_socket_timeout', 300); 
//     $texts = [
//         'reflection' => $request->reflection,
//         'observation' => $request->observation,
//         'childvoice' => $request->childvoice,
//         'futureplan' => $request->futureplan,
//         'analysis' => $request->analysis,
//         'eylf' => $request->eylf,
//         'development_milestone' => $request->development_milestone,
//         'montessori_assesment' => $request->montessori_assesment
//     ];

//     $apiKey = 'sk-d1febdfb38e3491391e5ca4ce911be5c';
//     $language = $request->language;

//     // run requests in parallel
//     $responses = Http::pool(function ($pool) use ($texts, $language, $apiKey) {
//         $promises = [];

//         foreach ($texts as $key => $text) {
//             if (!empty($text)) {
//                 $prompt = "Translate the following section to {$language}, including text inside parentheses.
//                 Maintain the section labels ({$key}) in English.
//                 Translate only the content after each label.
//                 Keep the structure exactly the same.

//                 Text:
//                 {$text}

//                 Translated version:";

//                 $promises[$key] = $pool->withHeaders([
//                     'Authorization' => "Bearer {$apiKey}",
//                     'Content-Type' => 'application/json',
//                 ])
//                  ->timeout(120) // Increased from 60 to 120 seconds
//         ->connectTimeout(120) // Connection timeout
//         ->retry(3, 2000, function ($exception, $request) {
//              // Retry only on timeout or connection errors
//             return $exception instanceof \Illuminate\Http\Client\ConnectionException;
//          })->post('https://api.deepseek.com/chat/completions', [
//                     "model" => "deepseek-chat",
//                     "messages" => [
//                         ["role" => "system", "content" => "You translate text while preserving section labels and structure."],
//                         ["role" => "user", "content" => $prompt]
//                     ],
//                     "temperature" => 1.3
//                 ]);
//             }
//         }

//         return $promises;
//     });

//     Log::info('Raw responses:', $responses);

//     // map results
//     $translatedData = [];
//     foreach ($texts as $key => $text) {
//         if (!empty($text) && isset($responses[$key])) {
//             $data = $responses[$key]->json();


//              Log::info("Translation response for {$key}: " . json_encode($data));

//             $translatedData[$key] = $data['choices'][0]['message']['content'] ?? '';
//         } else {
//                        Log::error("API Error for {$key}: " . $response->status() . " - " . $response->body());
//             $translatedData[$key] = '';
//         }
//     }

//     return response()->json([
//         'status' => true,
//         'message' => 'translated successfully',
//         'data' => $translatedData
//     ]);
// }

// public function TranslateObservation(Request $request)
// {
//     ini_set('max_execution_time', 300);
//     ini_set('default_socket_timeout', 300);
    
//     $texts = [
//         'reflection' => $request->reflection,
//         'observation' => $request->observation,
//         'childvoice' => $request->childvoice,
//         'futureplan' => $request->futureplan,
//         'analysis' => $request->analysis,
//         'eylf' => $request->eylf,
//         'development_milestone' => $request->development_milestone,
//         'montessori_assesment' => $request->montessori_assesment
//     ];

//     $apiKey = 'sk-d1febdfb38e3491391e5ca4ce911be5c';
//     $language = $request->language;

//     // Debug: Check if texts are actually being received
//     Log::info('Input texts:', $texts);
//     Log::info('Language: ' . $language);

//     $responses = Http::pool(function ($pool) use ($texts, $language, $apiKey) {
//         $promises = [];

//         foreach ($texts as $key => $text) {
//             if (!empty($text)) {
//                 $prompt = "Translate the following section to {$language}, including text inside parentheses.
//                 Maintain the section labels ({$key}) in English.
//                 Translate only the content after each label.
//                 Keep the structure exactly the same.

//                 Text:
//                 {$text}

//                 Translated version:";

//                 $promises[$key] = $pool->withHeaders([
//                     'Authorization' => "Bearer {$apiKey}",
//                     'Content-Type' => 'application/json',
//                 ])
//                 ->timeout(120)
//                 ->connectTimeout(120)
//                 ->retry(3, 2000, function ($exception, $request) {
//                     return $exception instanceof \Illuminate\Http\Client\ConnectionException;
//                 })
//                 ->post('https://api.deepseek.com/chat/completions', [
//                     "model" => "deepseek-chat",
//                     "messages" => [
//                         ["role" => "system", "content" => "You translate text while preserving section labels and structure."],
//                         ["role" => "user", "content" => $prompt]
//                     ],
//                     "temperature" => 1.3
//                 ]);
//             }
//         }

//         return $promises;
//     });

//     // Debug: Check the raw responses
//     Log::info('Raw responses:', $responses);

//     $translatedData = [];
//   $keys = array_keys($texts);

// foreach ($keys as $i => $key) {
//     $response = $responses[$i];

//     if (!empty($texts[$key]) && $response && $response->successful()) {
//         $data = $response->json();
//         $translatedData[$key] = $data['choices'][0]['message']['content'] ?? '';
//     } else {
//         $translatedData[$key] = '';
//     }
// }

//     return response()->json([
//         'status' => true,
//         'message' => 'translated successfully',
//         'data' => $translatedData
//     ]);
// }

public function TranslateObservation(Request $request)
{
    ini_set('max_execution_time', 300);
    ini_set('default_socket_timeout', 300);

    $texts = [
        'reflection'              => $request->reflection,
        'observation'             => $request->observation,
        'childvoice'              => $request->childvoice,
        'futureplan'              => $request->futureplan,
        'analysis'                => $request->analysis,
        'eylf'                    => $request->eylf,
        'development_milestone'   => $request->development_milestone,
        'montessori_assesment'    => $request->montessori_assesment
    ];

    $apiKey   = 'sk-d1febdfb38e3491391e5ca4ce911be5c'; 
    $language = $request->language;

    // Log::info('Input texts:', $texts);
    // Log::info('Language: ' . $language);

    // keep track of which keys we are actually sending
    $keysToTranslate = [];

    $responses = Http::pool(function ($pool) use ($texts, $language, $apiKey, &$keysToTranslate) {
        $requests = [];

        foreach ($texts as $key => $text) {
            if (!empty($text)) {
                $keysToTranslate[] = $key; // mark this key as active

                $prompt = "Translate the following section to {$language}, including text inside parentheses.
                Translate only the content after each label.
                Keep the structure exactly the same.

                Text:
                {$text}

                Translated version:";

                $requests[] = $pool->withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type'  => 'application/json',
                ])
                ->timeout(120)
                ->connectTimeout(120)
                ->retry(3, 2000, function ($exception) {
                    return $exception instanceof \Illuminate\Http\Client\ConnectionException;
                })
                ->post('https://api.deepseek.com/chat/completions', [
                    "model"     => "deepseek-chat",
                    "messages"  => [
                        ["role" => "system", "content" => "You translate text while preserving section labels and structure."],
                        ["role" => "user", "content" => $prompt]
                    ],
                     "temperature" => 1.3,    // DeepSeek's recommendation
                    "top_p" => 0.9,          // Faster sampling
                    "max_tokens" => 800,     // Reasonable limit
                    "stream" => false    
                ]);
            }
        }

        return $requests;
    });

    // Log::info('Raw responses:', $responses);

    // Map results back to keys
    $translatedData = [];
    $responseIndex  = 0;

    foreach ($texts as $key => $text) {
        if (!empty($text) && isset($keysToTranslate[$responseIndex])) {
            $response = $responses[$responseIndex];

            if ($response && $response->successful()) {
                $data = $response->json();
                $translatedData[$key] = $data['choices'][0]['message']['content'] ?? '';
            } else {
                $translatedData[$key] = '';
            }

            $responseIndex++;
        } else {
            // text was empty → just keep empty
            $translatedData[$key] = '';
        }
    }

    return response()->json([
        'status'  => true,
        'message' => 'translated successfully',
        'data'    => $translatedData
    ]);
}



public function shareObservation(Request $request)
{
    $request->validate([
        'recipient_email' => 'required|email',
        'message'         => 'nullable|string',
        'obsId'           => 'required'
    ]);

    $email   = $request->recipient_email;
    $message = $request->message ?? 'Please check the report link below.';
    $obsId   = $request->obsId;

    try {
        // ✅ Generate the shareable URL
        $url = route('sharelink' , $obsId);

        // ✅ Send email
        Mail::send([], [], function ($mail) use ($email, $url, $message, $obsId) {
            $mail->from('mydairee47@gmail.com', 'Observation Report')
                 ->to($email)
                 ->subject('Observation Report - ' . $obsId)
                 ->html("
                    <p>{$message}</p>
                    <p>You can view the observation by clicking the link below:</p>
                    <p><a href='{$url}' target='_blank'>{$url}</a></p>
                ");
        });

        return back()->with('success', 'Observation shared successfully!');

    } catch (\Exception $e) {
        return back()->with('error', 'Error occurred while sending the email: ' . $e->getMessage());
    }
}

    public function refine(Request $request)
    {
        $text = $request->input('text');

        if (!$text) {
            return response()->json([
                'status' => 'error',
                'message' => 'No text provided.'
            ]);
        }

        $refinedText = $this->callAIRefiner($text);

        return response()->json([
            'status' => 'success',
            'refined_text' => $refinedText
        ]);
    }

    private function callAIRefiner($text)
    {
        $apiKey = 'sk-d1febdfb38e3491391e5ca4ce911be5c'; // replace with your key
        $response = Http::withHeaders([
            'Authorization' => "Bearer $apiKey",
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/chat/completions', [
            "model" => "deepseek-chat",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "Polish the text for grammar, clarity, and professionalism. Add relevant content where appropriate. Return only the revised text without any explanations. Make it precise."
                ],
                [
                    "role" => "user",
                    "content" => $text
                ]
            ]
        ]);

        $json = $response->json();

        return $json['choices'][0]['message']['content'] ?? $text;
    }


// function AiAssistance(Request $request){

//     $observation = $request->observation;
//     // dd($observation);
    
//    $response = $this->AiAssistanceRefiner($observation);

//    $response = is_array($response) ? $response : json_decode($response, true);

// $analysis = $reflection = $futurePlan = $childVoice = null;

// if( $response ){
// $childVoice = $response['child_voice'];
// $reflection = $response['reflection'];
// $analysis = $response['analysis'];
// $futurePlan = $response['future_plan'];
// }



// $data = [
//     'raw' => $response,
//  'analysis'   => $analysis,
//     'reflection' => $reflection,
//     'futurePlan' => $futurePlan,
//     'childVoice' => $childVoice,

    
// ];

// return response()->json([
//     'status' => true,
//     'message' => 'Text retrieved successfully',
//     'data' => $data

   
// ]);

// }

//     private function AiAssistanceRefiner($observation)
//     {
//         $apiKey = 'sk-d1febdfb38e3491391e5ca4ce911be5c'; 
//         $response = Http::withHeaders([
//             'Authorization' => "Bearer $apiKey",
//             'Content-Type' => 'application/json',
//         ])
//         ->timeout(60)
//         ->retry(3, 2000)->post('https://api.deepseek.com/chat/completions', [
//             "model" => "deepseek-chat",
//                     "messages" => [
//                 [
//                     "role" => "system",
//                     "content" => "You are an assistant that rewrites observations. As an expert in child development, please generate an analysis and evaluation of daily activities, include reflections on progress and challenges, outline future plans to support growth, and incorporate authentic child voice using simple, everyday English suitable for a general audience. Always output in JSON with the following keys: analysis, reflection, future_plan, child_voice."
//                 ],
//                 [
//                     "role" => "user",
//                     "content" => "Observation: \"$observation\""
//                 ]
// ]

//         ]);

//         $json = $response->json();

//         return $json['choices'][0]['message']['content'] ?? $observation;
//     }

//     public function AiAssistance(Request $request)
// {
//     ini_set('max_execution_time', 300);
//     ini_set('default_socket_timeout', 300);

//     $observation = $request->observation;
//     $apiKey      = 'sk-d1febdfb38e3491391e5ca4ce911be5c';

//     // Define sections & their custom prompts
//     $sections = [
//         'analysis'    => "You are an assistant that rewrites observations. As an expert in child development, please generate an analysis and evaluation of daily activities using simple, everyday English suitable for a general audience",
//         'reflection'  => "You are an assistant that rewrites observations. As an expert in child development, please generate an include reflections on progress and challenges using simple, everyday English suitable for a general audience",
//         'future_plan' => "You are an assistant that rewrites observations. As an expert in child development, please generate an outline future plans to support growth using simple, everyday English suitable for a general audience",
      
//     ];

//     $responses = Http::pool(function ($pool) use ($sections, $apiKey) {
//         $requests = [];

//         foreach ($sections as $key => $prompt) {
//             $requests[$key] = $pool->withHeaders([
//                 'Authorization' => "Bearer {$apiKey}",
//                 'Content-Type'  => 'application/json',
//             ])
//             ->timeout(120)
//             ->connectTimeout(120)
//             ->retry(3, 2000, function ($exception) {
//                 return $exception instanceof \Illuminate\Http\Client\ConnectionException;
//             })
//             ->post('https://api.deepseek.com/chat/completions', [
//                 "model"    => "deepseek-chat",
//                 "messages" => [
//                     ["role" => "system", "content" => "You are an assistant that rewrites observations in JSON-friendly format."],
//                     ["role" => "user", "content" => $prompt]
//                 ],
//                 "temperature" => 0.7,
//                 "max_tokens"  => 600,
//                 "stream"      => false
//             ]);
//         }

//         return $requests;
//     });

//     // Map responses back
//   $finalData = [];
//     $responseIndex  = 0;

//     foreach ($sections as $key => $prompt) {
//         if (!empty($sections) && isset($requests[$responseIndex])) {
//             $response = $responses[$responseIndex];

//             if ($response && $response->successful()) {
//                 $data = $response->json();
//                 $finalData[$key] = $data['choices'][0]['message']['content'] ?? '';
//             } else {
//                 $finalData[$key] = '';
//             }

//             $responseIndex++;
//         } else {
//             // text was empty → just keep empty
//             $finalData[$key] = '';
//         }
//     }

//     return response()->json([
//         'status'  => true,
//         'message' => 'AI assistance generated successfully',
//         'data'    => $finalData
//     ]);
// }

// public function AiAssistance(Request $request)
// {
//     ini_set('max_execution_time', 300);
//     ini_set('default_socket_timeout', 300);

//     $observation = $request->observation;
//     $apiKey      = 'sk-d1febdfb38e3491391e5ca4ce911be5c';

//     // Sections with observation included
//     $sections = [
//         'analysis'    => "Generate an analysis and evaluation of the following observation, highlighting progress and challenges in child development. Use simple, everyday English.\n\nObservation:\n{$observation}",
//         'reflection'  => "Write a reflection on the following observation, including educator’s perspective, progress, and challenges in simple, everyday English.\n\nObservation:\n{$observation}",
//         'future_plan' => "Suggest clear and actionable future plans to support the child’s growth based on the following observation. Use simple, everyday English.\n\nObservation:\n{$observation}"
//     ];

//     // Keep mapping of index → key
//     $keys = array_keys($sections);

//     $responses = Http::pool(function ($pool) use ($sections, $apiKey) {
//         $requests = [];
//         foreach ($sections as $prompt) {
//             $requests[] = $pool->withHeaders([
//                 'Authorization' => "Bearer {$apiKey}",
//                 'Content-Type'  => 'application/json',
//             ])
//             ->timeout(120)
//             ->connectTimeout(120)
//             ->retry(3, 2000, function ($exception) {
//                 return $exception instanceof \Illuminate\Http\Client\ConnectionException;
//             })
//             ->post('https://api.deepseek.com/chat/completions', [
//                 "model"    => "deepseek-chat",
//                 "messages" => [
//                     ["role" => "system", "content" => "You are an assistant that rewrites observations. Always respond in plain text (not JSON)."],
//                     ["role" => "user", "content" => $prompt]
//                 ],
//                 "temperature" => 0.7,
//                 "max_tokens"  => 600,
//                 "stream"      => false
//             ]);
//         }
//         return $requests;
//     });

//     // Map back results
//     $finalData = [];
//     foreach ($responses as $index => $response) {
//         $key = $keys[$index]; // correct mapping
//         if ($response && $response->successful()) {
//             $data = $response->json();

//             // 🔍 Debug: log full response
//             \Log::info("AI Response for {$key}:", $data);

//             $finalData[$key] = trim($data['choices'][0]['message']['content'] ?? '');
//         } else {
//             \Log::error("AI request failed for {$key}", [
//                 'status' => $response ? $response->status() : 'no response',
//                 'body'   => $response ? $response->body() : 'empty'
//             ]);
//             $finalData[$key] = '';
//         }
//     }

//     // 🔍 Debug final mapping
//     \Log::info('Final AI Assistance Data:', $finalData);

//     return response()->json([
//         'status'  => true,
//         'message' => 'AI assistance generated successfully',
//         'data'    => $finalData
//     ]);
// }

// public function AiAssistance(Request $request)
// {
//     ini_set('max_execution_time', 300);
//     ini_set('default_socket_timeout', 300);

//     $observation = $request->observation;
//     $apiKey      = 'sk-d1febdfb38e3491391e5ca4ce911be5c';

//     // Sections with JSON instructions
//     $sections = [
//         'analysis'    => "Based on the observation below, generate an analysis and evaluation of daily activities, focusing on progress and challenges in child development. 
//         Respond strictly in JSON as {\"analysis\": [\"point 1\", \"point 2\", ...]}\n\nObservation:\n{$observation}",
        
//         'reflection'  => "Based on the observation below, write reflections from the educator’s perspective, highlighting strengths and areas for growth. 
//         Respond strictly in JSON as {\"reflection\": [\"point 1\", \"point 2\", ...]}\n\nObservation:\n{$observation}",
        
//         'future_plan' => "Based on the observation below, suggest practical and actionable future plans to support the child’s growth. 
//         Respond strictly in JSON as {\"future_plan\": [\"point 1\", \"point 2\", ...]}\n\nObservation:\n{$observation}"
//     ];

//     $keys = array_keys($sections);

//     $responses = Http::pool(function ($pool) use ($sections, $apiKey) {
//         $requests = [];
//         foreach ($sections as $prompt) {
//             $requests[] = $pool->withHeaders([
//                 'Authorization' => "Bearer {$apiKey}",
//                 'Content-Type'  => 'application/json',
//             ])
//             ->timeout(120)
//             ->connectTimeout(120)
//             ->retry(3, 2000, function ($exception) {
//                 return $exception instanceof \Illuminate\Http\Client\ConnectionException;
//             })
//             ->post('https://api.deepseek.com/chat/completions', [
//                 "model"    => "deepseek-chat",
//                 "messages" => [
//                     ["role" => "system", "content" => "Always respond strictly in JSON format. Do not include extra text."],
//                     ["role" => "user", "content" => $prompt]
//                 ],
//                 "temperature" => 0.7,
//                 "max_tokens"  => 800,
//                 "stream"      => false
//             ]);
//         }
//         return $requests;
//     });

//     // Parse responses
//     $finalData = [];
//     foreach ($responses as $index => $response) {
//         $key = $keys[$index];
//         if ($response && $response->successful()) {
//             $data = $response->json();

//             // Debug log full raw response
//             \Log::info("AI Raw Response for {$key}:", $data);

//             $content = $data['choices'][0]['message']['content'] ?? '';

//             // Try parsing JSON
//             $decoded = json_decode($content, true);
//             if (json_last_error() === JSON_ERROR_NONE) {
//                 // If model wrapped in array properly
//                 $finalData[$key] = $decoded[$key] ?? [];
//             } else {
//                 // fallback → keep raw string
//                 $finalData[$key] = [$content];
//             }
//         } else {
//             \Log::error("AI request failed for {$key}", [
//                 'status' => $response ? $response->status() : 'no response',
//                 'body'   => $response ? $response->body() : 'empty'
//             ]);
//             $finalData[$key] = [];
//         }
//     }

//     // Debug final structured output
//     \Log::info('Final Structured AI Assistance Data:', $finalData);

//     return response()->json([
//         'status'  => true,
//         'message' => 'AI assistance generated successfully',
//         'data'    => $finalData
//     ]);
// }



public function AiAssistance(Request $request) 
{
    ini_set('max_execution_time', 300);
    ini_set('default_socket_timeout', 300);

    $observation = $request->observation;
    $apiKey = 'sk-d1febdfb38e3491391e5ca4ce911be5c';

    if (empty($observation)) {
        return response()->json([
            'status' => false,
            'message' => 'Observation is required',
            'data' => []
        ], 400);
    }

    $sections = [
        'analysis' => "Based on the observation below, generate an analysis and evaluation of daily activities, focusing on progress and challenges in child development.

Respond strictly in JSON format as {\"analysis\": [\"point 1\", \"point 2\", ...]}

Observation:
{$observation}",

        'reflection' => "Based on the observation below, write reflections from the educator's perspective, highlighting strengths and areas for growth.

Respond strictly in JSON format as {\"reflection\": [\"point 1\", \"point 2\", ...]}

Observation:
{$observation}",

        'future_plan' => "Based on the observation below, suggest practical and actionable future plans to support the child's growth.

Respond strictly in JSON format as {\"future_plan\": [\"point 1\", \"point 2\", ...]}

Observation:
{$observation}"
    ];

    try {
        // Store keys in order for mapping
        $sectionKeys = array_keys($sections);
        
        $responses = Http::pool(function ($pool) use ($sections, $apiKey) {
            $requests = [];
            foreach ($sections as $key => $prompt) {
                $requests[] = $pool->withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ])
                ->timeout(120)
                ->connectTimeout(120)
                ->retry(3, 2000)
                ->post('https://api.deepseek.com/chat/completions', [
                    "model" => "deepseek-chat",
                    "messages" => [
                        [
                            "role" => "system", 
                            "content" => "You are a helpful assistant that always responds in valid JSON format. Do not include any text outside the JSON structure."
                        ],
                        [
                            "role" => "user", 
                            "content" => $prompt
                        ]
                    ],
                    "temperature" => 0.7,
                    "max_tokens" => 800,
                    "stream" => false
                ]);
            }
            return $requests;
        });

        $finalData = [];
        
        // Map responses back to their corresponding keys
        foreach ($responses as $index => $response) {
            $key = $sectionKeys[$index]; // Use the stored key order
            
            if ($response->successful()) {
                $data = $response->json();
                Log::info("AI Raw Response for {$key}:", $data);

                if (isset($data['choices'][0]['message']['content'])) {
                    $content = trim($data['choices'][0]['message']['content']);
                    
                    // Clean up content
                    $content = preg_replace('/^```json\s*/', '', $content);
                    $content = preg_replace('/\s*```$/', '', $content);
                    $content = preg_replace('/^[^{\[]*/', '', $content);
                    $content = preg_replace('/[^}\]]*$/', '', $content);
                    
                    $decoded = json_decode($content, true);
                    
                    if (json_last_error() === JSON_ERROR_NONE) {
                        Log::info("Decoded JSON for {$key}:", $decoded);
                        
                        if (isset($decoded[$key]) && is_array($decoded[$key])) {
                            $finalData[$key] = $decoded[$key];
                        } else {
                            // Find any array in the response
                            $found = false;
                            foreach ($decoded as $responseValue) {
                                if (is_array($responseValue) && !empty($responseValue)) {
                                    $finalData[$key] = $responseValue;
                                    $found = true;
                                    break;
                                }
                            }
                            
                            if (!$found) {
                                $finalData[$key] = is_array($decoded) ? $decoded : [$decoded];
                            }
                        }
                    } else {
                        Log::error("JSON parsing failed for {$key}", [
                            'json_error' => json_last_error_msg(),
                            'content' => $content
                        ]);
                        $finalData[$key] = $this->extractFallbackContent($content, $key);
                    }
                } else {
                    Log::error("Missing content in AI response for {$key}", $data);
                    $finalData[$key] = [];
                }
            } else {
                Log::error("AI request failed for {$key}", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                $finalData[$key] = [];
            }
        }

        // Validate data
        $hasData = array_filter($finalData, function($data) {
            return !empty($data);
        });

        if (empty($hasData)) {
            return response()->json([
                'status' => false,
                'message' => 'No data could be retrieved from AI service',
                'data' => $finalData
            ], 500);
        }

        Log::info('Final Structured AI Assistance Data:', $finalData);

        return response()->json([
            'status' => true,
            'message' => 'AI assistance generated successfully',
            'data' => $finalData
        ]);

    } catch (\Exception $e) {
        Log::error('AI Assistance Error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'status' => false,
            'message' => 'An error occurred while processing AI assistance: ' . $e->getMessage(),
            'data' => []
        ], 500);
    }
}

private function formatResponseData($dataArray)
{
    if (!is_array($dataArray) || empty($dataArray)) {
        return $dataArray;
    }

    $formattedData = [];
    
    foreach ($dataArray as $item) {
        if (is_string($item)) {
            // Check if the item appears to be in numbered or bullet point format
            if ($this->isBulletPointFormat($item)) {
                // Convert to HTML bullet points
                $formattedData[] = $this->convertToBulletPoint($item);
            } else {
                // Keep as paragraph format
                $formattedData[] = $this->formatAsParagraph($item);
            }
        } else {
            $formattedData[] = $item;
        }
    }
    
    return $formattedData;
}

private function isBulletPointFormat($text)
{
    // Check if text starts with numbers (1., 2., etc.) or bullet markers (-, *, •)
    $patterns = [
        '/^\d+\.\s+/',           // 1. 2. 3.
        '/^\d+\)\s+/',           // 1) 2) 3)
        '/^[\-\*\•]\s+/',        // - * •
        '/^[IVX]+\.\s+/',        // I. II. III.
        '/^[a-z]\.\s+/',         // a. b. c.
        '/^[A-Z]\.\s+/',         // A. B. C.
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, trim($text))) {
            return true;
        }
    }
    
    return false;
}

private function convertToBulletPoint($text)
{
    // Remove existing numbering or bullet markers and clean up
    $cleanText = preg_replace('/^\d+[\.\)]\s*/', '', trim($text));
    $cleanText = preg_replace('/^[\-\*\•]\s*/', '', $cleanText);
    $cleanText = preg_replace('/^[IVX]+\.\s*/', '', $cleanText);
    $cleanText = preg_replace('/^[a-zA-Z][\.\)]\s*/', '', $cleanText);
    
    return '• ' . trim($cleanText);
}

private function formatAsParagraph($text)
{
    // Keep as is for paragraph format, just ensure it's clean
    return trim($text);
}

private function extractFallbackContent($content, $key)
{
    $lines = explode("\n", $content);
    $points = [];
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        $line = preg_replace('/^[\-\*\d+\.\)\s]+/', '', $line);
        $line = trim($line);
        
        if (!empty($line) && strlen($line) > 10) {
            $points[] = $line;
        }
    }
    
    return empty($points) ? [$content] : $points;
}


    public function index()
    {

        $authId = Auth::user()->userid;
        $centerid = Session('user_center_id');

        if (Auth::user()->userType == "Superadmin") {
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }

        if (Auth::user()->userType == "Superadmin") {

            $observations = Observation::with(['user', 'child', 'media', 'Seen.user','comments'])
                ->where('centerid', $centerid)
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page
                

        } elseif (Auth::user()->userType == "Staff") {

            $observations = Observation::with([
                'user',
                'child',
                'media',
                'Seen.user',
                'comments'
            ])
            ->where(function ($query) use ($authId) {
                // Observations created by the staff
                $query->where('userId', $authId);
        
                // Or Observations where staff is tagged
                $taggedObservationIds = ObservationStaff::where('userid', $authId)
                    ->pluck('observationId')
                    ->toArray();
        
                if (!empty($taggedObservationIds)) {
                    $query->orWhereIn('id', $taggedObservationIds);
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
    //         $observations = Observation::with(['user', 'child', 'media', 'Seen.user', 'comments'])
    // ->where('userid', $authId) // your created
    // ->orWhereRaw("FIND_IN_SET(?, tagged_staff)", [$authId]) // your tagged
    // ->orderBy('id', 'desc')
    // ->paginate(10);

    // $observations = Observation::with(['user', 'child', 'media', 'Seen.user', 'comments'])
    // ->where(function ($q) use ($authId) {
    //     $q->where('userid', $authId)
    //       ->orWhereRaw("FIND_IN_SET(?, COALESCE(tagged_staff, ''))", [$authId]);
    // })
    // ->orderBy('id', 'desc')
    // ->paginate(10);



        } else {

            $childids = Childparent::where('parentid', $authId)->pluck('childid');
            $observationIds = ObservationChild::whereIn('childId', $childids)
                ->pluck('observationId')
                ->unique()
                ->toArray();
            // dd($childids);
            $observations = Observation::with(['user', 'child', 'media', 'Seen.user','comments'])
                ->whereIn('id', $observationIds)
                ->where('status',"Published")
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page

        }

        //  dd($observations);

        return view('observations.index', compact('observations', 'centers'));
    }



    public function applyFilters(Request $request)
    {
        //    dd($request->all());
        try {

            $centerid = Session('user_center_id');


            $query = Observation::with(['user', 'child', 'media', 'Seen.user','comments'])
                ->where('centerid', $centerid);
            // Status filter
            if ($request->has('observations') && !empty($request->observations)) {
                $statusFilters = $request->observations;
                if (!in_array('All', $statusFilters)) {
                    $query->whereIn('status', $statusFilters);
                }
            }

            // Date filter
            if ($request->has('added') && !empty($request->added)) {
                $dateFilters = $request->added;

                foreach ($dateFilters as $dateFilter) {
                    switch ($dateFilter) {
                        case 'Today':
                            $query->whereDate('created_at', Carbon::today());
                            break;

                        case 'This Week':
                            $query->whereBetween('created_at', [
                                Carbon::now()->startOfWeek(),
                                Carbon::now()->endOfWeek()
                            ]);
                            break;

                        case 'This Month':
                            $query->whereBetween('created_at', [
                                Carbon::now()->startOfMonth(),
                                Carbon::now()->endOfMonth()
                            ]);
                            break;

                        case 'Custom':
                            if ($request->fromDate && $request->toDate) {
                                $fromDate = Carbon::parse($request->fromDate)->startOfDay();
                                $toDate = Carbon::parse($request->toDate)->endOfDay();
                                $query->whereBetween('created_at', [$fromDate, $toDate]);
                            }
                            break;
                    }
                }
            }

            // Child filter
            if ($request->has('childs') && !empty($request->childs)) {
                $childIds = $request->childs;

                // Get observation IDs that have the selected children
                $observationIds = ObservationChild::whereIn('childId', $childIds)
                    ->pluck('observationId')
                    ->unique()
                    ->toArray();

                if (!empty($observationIds)) {
                    $query->whereIn('id', $observationIds);
                } else {
                    // If no observations found for selected children, return empty result
                    $query->where('id', 0);
                }
            }

            // Author filter
            if ($request->has('authors') && !empty($request->authors)) {
                $authorFilters = $request->authors;

                // 🚫 Skip filter if "Any" is selected
                if (!in_array('Any', $authorFilters)) {

                    // ✅ If "Me" is selected
                    if (in_array('Me', $authorFilters)) {
                        $query->where('userId', Auth::id());
                    }

                    // ✅ If specific staff IDs are selected (as string IDs)
                    else {
                        $query->whereIn('userId', $authorFilters);
                    }
                }
            }


            $user = Auth::user();
            if ($user->userType === 'Staff') {
                $query->where('userId', Auth::id());
            }

            // Apply user-specific filters based on role
            // $user = Auth::user();
            // if ($user->userType === 'Parent') {
            //     // Parents can only see observations of their children
            //     $userChildIds = $user->children()->pluck('id')->toArray();
            //     if (!empty($userChildIds)) {
            //         $parentObservationIds = ObservationChild::whereIn('childId', $userChildIds)
            //             ->pluck('observationId')
            //             ->unique()
            //             ->toArray();

            //         if (!empty($parentObservationIds)) {
            //             $query->whereIn('id', $parentObservationIds);
            //         } else {
            //             $query->where('id', 0);
            //         }
            //     }
            // } elseif ($user->userType === 'Teacher') {
            //     // Teachers can see observations of children in their classes
            //     // Adjust this logic based on your relationship structure
            //     $teacherChildIds = $user->teacherChildren()->pluck('id')->toArray();
            //     if (!empty($teacherChildIds)) {
            //         $teacherObservationIds = ObservationChild::whereIn('childId', $teacherChildIds)
            //             ->pluck('observationId')
            //             ->unique()
            //             ->toArray();

            //         if (!empty($teacherObservationIds)) {
            //             $query->whereIn('id', $teacherObservationIds);
            //         } else {
            //             $query->where('id', 0);
            //         }
            //     }
            // }

            // Order by latest first
            $query->orderBy('created_at', 'desc');

            // Get the filtered observations
            $observations = $query->get();

            // Format the observations for response
            $formattedObservations = $observations->map(function ($observation) {
                return [
                    'id' => $observation->id,
                    'title' => html_entity_decode($observation->title ?? ''),
                    'obestitle' => $observation->obestitle ?? '',
                    'status' => $observation->status,
                    'media' => $observation->media->first(),
                    'mediaType' => $observation->observationsMediaType,
                    'userName' => $observation->user->name ?? 'Unknown',
                    'date_added' => Carbon::parse($observation->created_at)->format('d.m.Y'),
                    'created_at' => $observation->created_at->format('Y-m-d H:i:s'),
                    'seen' => $observation->seen->map(function ($seen) {
                        if ($seen->user && $seen->user->userType === 'Parent') {
                            return [
                                'name' => $seen->user->name,
                                'userType' => $seen->user->userType,
                                'imageUrl' => $seen->user->imageUrl,
                                'gender' => $seen->user->gender,
                            ];
                        }
                        return null;
                    })->filter(),

                  // Add comments data
        'comments' => $observation->comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'comments' => $comment->comments,
                'userId' => $comment->userId,
                'user_name' => $comment->user->name ?? 'Unknown',
                'created_at_human' => $comment->created_at->diffForHumans(),
                'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
            ];
        }),
        

                    'userRole' => Auth::user()->userType,
                    'currentUserId' => Auth::id(),
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Filters applied successfully',
                'observations' => $formattedObservations,
                'userRole' => Auth::user()->userType,
                'count' => $formattedObservations->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while applying filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get children list for filter modal
     */
    public function getChildren(Request $request)
    {
        try {
            $user = Auth::user();
            $children = collect();

            if ($user->userType === 'Superadmin') {
                $children = $this->getChildrenForSuperadmin();
            } elseif ($user->userType === 'Staff') {
                $children = $this->getChildrenForStaff();
            } else {
                $children = $this->getChildrenForParent();
            }

            return response()->json([
                'children' => $children,
                'status' => 'success',
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while applying filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    private function getChildrenForSuperadmin()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        // Get all room IDs for the center
        $roomIds = Room::where('centerid', $centerid)->pluck('id');

        // Get all children in those rooms
        $children = Child::whereIn('room', $roomIds)->where('status','Active')->get();

        return $children;
    }


    private function getChildrenForStaff()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        // Get room IDs from RoomStaff where staff is assigned
        $roomIdsFromStaff = RoomStaff::where('staffid', $authId)->pluck('roomid');

        // Get room IDs where user is the owner (userId matches)
        $roomIdsFromOwner = Room::where('userId', $authId)->pluck('id');

        // Merge both collections and remove duplicates
        $allRoomIds = $roomIdsFromStaff->merge($roomIdsFromOwner)->unique();

        // Get all children in those rooms
        $children = Child::whereIn('room', $allRoomIds)->where('status','Active')->get();

        return $children;
    }

    private function getChildrenForParent()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        $childids = Childparent::where('parentid', $authId)->pluck('childid');

        $children = Child::whereIn('id', $childids)->where('status','Active')->get();

        return $children;
    }

    private function getStaffForSuperadmin()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        // Get all room IDs for the center
        $usersid = Usercenter::where('centerid', $centerid)->pluck('userid')->toArray();

        // Exclude current user and Superadmins
        $staff = User::whereIn('id', $usersid)
            ->where('userType', 'Staff')
            ->where('status','ACTIVE')
            ->get();

        return $staff;
    }


    public function getStaff(Request $request)
    {
        // dd('here');
        try {
            $user = Auth::user();
            $children = collect();

            if ($user->userType === 'Superadmin' || $user->userType === 'Staff') {
                $staff = $this->getStaffForSuperadmin();
            }
            // elseif($user->userType === 'Staff'){
            // $children = $this->getChildrenForStaff();
            // }else{
            // $children = $this->getChildrenForParent();
            // }

            return response()->json([
                'staff' => $staff,
                'status' => 'success',
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while applying filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    private function getroomsforSuperadmin()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        $rooms = Room::where('centerid', $centerid)->get();
        return $rooms;
    }

    private function getroomsforStaff()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        $roomIdsFromStaff = RoomStaff::where('staffid', $authId)->pluck('roomid');

        // Get room IDs where user is the owner (userId matches)
        $roomIdsFromOwner = Room::where('userId', $authId)->pluck('id');

        // Merge both collections and remove duplicates
        $allRoomIds = $roomIdsFromStaff->merge($roomIdsFromOwner)->unique();

        $rooms = Room::whereIn('id', $allRoomIds)->get();
        return $rooms;
    }



    public function getrooms()
    {
        try {
            $user = Auth::user();
            $rooms = collect();

            if ($user->userType === 'Superadmin') {
                $rooms = $this->getroomsforSuperadmin();
            } else {
                $rooms = $this->getroomsforStaff();
            }

            return response()->json([
                'rooms' => $rooms,
                'status' => 'success',
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while applying filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function storepage($id = null, $activeTab = 'observation', $activesubTab = 'MONTESSORI')
    {
        // dd($id);
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        if (Auth::user()->userType == "Superadmin") {
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }

        $observation = null;
        if ($id) {
            $observation = Observation::with(['media', 'child.child', 'montessoriLinks', 'eylfLinks', 'devMilestoneSubs', 'links'])->find($id);
        }

        $childrens = $observation
            ? $observation->child->pluck('child')->filter()
            : collect();


        $rooms = collect();

        $educators = collect();
    
if ($observation && !empty($observation->tagged_staff)){
        $taggededucators = explode(',',$observation->tagged_staff);
        $educators = User::whereIn('userid',$taggededucators)->get();
        }


        // dd($educators);

        if ($observation && $observation->room) {
            $roomIds = explode(',', $observation->room); // Convert comma-separated string to array
            $rooms = Room::whereIn('id', $roomIds)->get();
        }

        $subjects = MontessoriSubject::with(['activities.subActivities'])->get();
        $outcomes = EYLFOutcome::with('activities.subActivities')->get();
        $milestones = DevMilestone::with('mains.subs')->get();

        return view('observations.storeObservation', compact('centers', 'observation', 'childrens', 'activeTab', 'rooms', 'activesubTab', 'subjects', 'outcomes', 'milestones','educators'));
    }


    public function storeMontessoriData(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'observationId' => 'required|exists:observation,id',
            'subactivities' => 'array',
            'subactivities.*.idSubActivity' => 'required|integer',
            'subactivities.*.assesment' => 'required|in:Introduced,Working,Completed',
        ]);

        // Delete existing data
        ObservationMontessori::where('observationId', $request->observationId)->delete();

        // Insert new data
        foreach ($request->subactivities as $entry) {
            ObservationMontessori::create([
                'observationId' => $request->observationId,
                'idSubActivity' => $entry['idSubActivity'],
                'assesment' => $entry['assesment'],
                'idExtra' => 0 // or other default
            ]);
        }

        $childIds = ObservationChild::where('observationId', $request->observationId)->pluck('childId')->toArray();

        Userprogressplan::where('observationId', $request->observationId)->delete();


        // Insert into Userprogressplan
        foreach ($childIds as $childId) {
            foreach ($request->subactivities as $entry) {
                Userprogressplan::create([
                    'observationId' => $request->observationId,
                    'childid'       => $childId,
                    'subid'         => $entry['idSubActivity'],
                    'status'        => $entry['assesment'],
                    'created_by'    => Auth::id(),
                    'created_at'    => now(),
                    'updated_by'    => Auth::id(),
                    'updated_at'    => now(),
                ]);
            }
        }


        return response()->json(['message' => 'Saved successfully', 'status' => 'success', 'id' => $request->observationId]);
    }


    public function storeEylfData(Request $request)
    {
        $request->validate([
            'observationId' => 'required|exists:observation,id',
            'subactivityIds' => 'array',
            'subactivityIds.*' => 'integer|exists:eylfsubactivity,id'
        ]);

        ObservationEYLF::where('observationId', $request->observationId)->delete();

        foreach ($request->subactivityIds ?? [] as $subId) {
            $activityid = EYLFSubActivity::find($subId)->activityid ?? null;
            if ($activityid) {
                ObservationEYLF::create([
                    'observationId' => $request->observationId,
                    'eylfSubactivityId' => $subId,
                    'eylfActivityId' => $activityid
                ]);
            }
        }

        return response()->json(['status' => 'success', 'id' => $request->observationId]);
    }



    public function storeDevMilestone(Request $req)
    {
        // dd($req->all());
        $req->validate([
            'observationId' => 'required|exists:observation,id',
            'selections' => 'array',
            'selections.*.idSub' => 'required|integer',
            'selections.*.assessment' => 'required|in:Introduced,Working towards,Achieved',
        ]);

        ObservationDevMilestoneSub::where('observationId', $req->observationId)->delete();

        foreach ($req->selections as $sel) {
            ObservationDevMilestoneSub::create([
                'observationId' => $req->observationId,
                'devMilestoneId' => $sel['idSub'],
                'assessment' => $sel['assessment']
            ]);
        }

        return response()->json(['status' => 'success', 'id' => $req->observationId]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'observationId' => 'required|exists:observation,id',
            'status' => 'required|in:Published,Draft'
        ]);

        $observation = Observation::find($request->observationId);
        $observation->status = $request->status;
        $observation->save();

        return response()->json(['status' => 'success', 'id' => $observation->id]);
    }


    public function view($id)
    {
        $observation = Observation::with([
            'media',
            'child.child',
            'montessoriLinks.subActivity.activity.subject',
            'eylfLinks.subActivity.activity.outcome',
            'devMilestoneSubs.devMilestone.main',
            'devMilestoneSubs.devMilestone.milestone'
        ])->findOrFail($id);

        return view('observations.view', compact('observation'));
    }

    public function linkobservationdata(Request $request)
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');
        $search = $request->query('search');
        $obsId = $request->query('obsId'); // Current observation ID for checking existing links

        $observations = Observation::with(['media', 'child.child', 'user'])
            ->when($search, function ($query, $search) {
                return $query->where('obestitle', 'like', "%{$search}%");
            })
            ->where('centerid', $centerid)
            ->orderBy('created_at', 'desc')
            ->get();

        $linkedIds = ObservationLink::where('observationId', $obsId)
            ->where('linktype', 'OBSERVATION')
            ->pluck('linkid')
            ->toArray();

        return response()->json([
            'observations' => $observations,
            'linked_ids' => $linkedIds
        ]);
    }


    public function storelinkobservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'obsId' => 'required|exists:observation,id',
            'observation_ids' => 'required|array',
            'observation_ids.*' => 'exists:observation,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $observationId = $request->input('obsId');

        ObservationLink::where('observationId', $observationId) ->where('linktype', 'OBSERVATION')->delete();

        $linkIds = $request->input('observation_ids');

        foreach ($linkIds as $linkId) {
            ObservationLink::create([
                'observationId' => $observationId,
                'linkid' => $linkId,
                'linktype' => 'OBSERVATION',
            ]);
        }

        return response()->json(['message' => 'Links saved successfully.', 'id' => $observationId]);
    }






    public function linkreflectiondata(Request $request)
{
    $authId = Auth::user()->id;
    $centerid = Session('user_center_id');
    $search = $request->query('search');
    $obsId = $request->query('obsId'); // Current observation ID for checking existing links

    $reflections = Reflection::with(['creator', 'center', 'children.child', 'media', 'staff.staff', 'Seen.user'])
        ->when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%");
        })
        ->where('centerid', $centerid)
        ->orderBy('created_at', 'desc')
        ->get();

    $linkedIds = ObservationLink::where('observationId', $obsId)
        ->where('linktype', 'REFLECTION')
        ->pluck('linkid')
        ->toArray();

    return response()->json([
        'reflections' => $reflections,
        'linked_ids' => $linkedIds
    ]);
}

public function storelinkreflection(Request $request)
{
    $validator = Validator::make($request->all(), [
        'obsId' => 'required|exists:observation,id',
        'reflection_ids' => 'required|array',
        'reflection_ids.*' => 'exists:reflection,id', // Adjust table name if needed
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $observationId = $request->input('obsId');

    // Remove existing reflection links for this observation
    ObservationLink::where('observationId', $observationId)
        ->where('linktype', 'REFLECTION')
        ->delete();

    $linkIds = $request->input('reflection_ids');

    foreach ($linkIds as $linkId) {
        ObservationLink::create([
            'observationId' => $observationId,
            'linkid' => $linkId,
            'linktype' => 'REFLECTION',
        ]);
    }

    return response()->json(['message' => 'Reflection links saved successfully.', 'id' => $observationId]);
}




public function linkprogramplandata(Request $request)
{
    $authId = Auth::user()->id;
    $centerid = Session('user_center_id');
    $search = $request->query('search');
    $obsId = $request->query('obsId'); // Current observation ID for checking existing links

    // Month names for search
    $monthNames = [
        'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4,
        'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
        'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12
    ];

    $programPlans = ProgramPlanTemplateDetailsAdd::with(['room', 'creator'])
        ->when($search, function ($query, $search) use ($monthNames) {
            $searchLower = strtolower($search);
            $monthNumber = null;
            
            // Check if search matches any month name
            foreach ($monthNames as $monthName => $monthNum) {
                if (strpos($monthName, $searchLower) !== false) {
                    $monthNumber = $monthNum;
                    break;
                }
            }
            
            if ($monthNumber) {
                return $query->where('months', $monthNumber);
            }
            
            // If no month found, search in year as well
            return $query->where('year', 'like', "%{$search}%");
        })
        ->where('status', 'Published')
        ->where('centerid', $centerid)
        ->orderBy('created_at', 'desc')
        ->get();

    $linkedIds = ObservationLink::where('observationId', $obsId)
        ->where('linktype', 'PROGRAMPLAN')
        ->pluck('linkid')
        ->toArray();

    return response()->json([
        'program_plans' => $programPlans,
        'linked_ids' => $linkedIds
    ]);
}

public function storelinkprogramplan(Request $request)
{
    $validator = Validator::make($request->all(), [
        'obsId' => 'required|exists:observation,id',
        'program_plan_ids' => 'required|array',
        'program_plan_ids.*' => 'exists:programplantemplatedetailsadd,id', // Adjust table name if needed
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $observationId = $request->input('obsId');

    // Remove existing program plan links for this observation
    ObservationLink::where('observationId', $observationId)
        ->where('linktype', 'PROGRAMPLAN')
        ->delete();

    $linkIds = $request->input('program_plan_ids');

    foreach ($linkIds as $linkId) {
        ObservationLink::create([
            'observationId' => $observationId,
            'linkid' => $linkId,
            'linktype' => 'PROGRAMPLAN',
        ]);
    }

    return response()->json(['message' => 'Program Plan links saved successfully.', 'id' => $observationId]);
}




    // public function store(Request $request)
    // {
    //     // Get PHP upload limits
    //     $uploadMaxSize = min(
    //         $this->convertToBytes(ini_get('upload_max_filesize')),
    //         $this->convertToBytes(ini_get('post_max_size'))
    //     );

    //     // Validate input
    //     $validator = Validator::make($request->all(), [
    //         'selected_rooms'   => 'required',
    //         'obestitle'        => 'required|string|max:255',
    //         'title'            => 'required|string|max:255',
    //         'notes'            => 'required|string',
    //         'reflection'       => 'required|string',
    //         'child_voice'      => 'required|string',
    //         'future_plan'      => 'required|string',
    //         'selected_children'=> 'required|string',
    //         'media'            => 'required|array|min:1',
    //         'media.*'          => "file|mimes:jpeg,png,jpg,gif,webp|max:" . intval($uploadMaxSize / 1024), // Convert to KB
    //     ], [
    //         'media.required' => 'At least one media file is required.',
    //         'media.*.max' => 'Each file must be smaller than ' . ($uploadMaxSize / 1024 / 1024) . 'MB.',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     DB::beginTransaction();

    //     try {
    //         $authId = Auth::user()->id;
    //         $centerid = Session('user_center_id');

    //         $observation = new Observation();
    //         $observation->room = $request->input('selected_rooms');
    //         $observation->obestitle = $request->input('obestitle');
    //         $observation->title = $request->input('title');
    //         $observation->notes = $request->input('notes');
    //         $observation->userId = $authId;
    //         $observation->centerid = $centerid;
    //         $observation->reflection = $request->input('reflection');
    //         $observation->child_voice = $request->input('child_voice');
    //         $observation->future_plan = $request->input('future_plan');
    //         $observation->save();

    //         $observationId = $observation->id;

    //         $selectedChildren = explode(',', $request->input('selected_children'));
    //         foreach ($selectedChildren as $childId) {
    //             if (trim($childId) !== '') {
    //                 ObservationChild::create([
    //                     'observationId' => $observationId,
    //                     'childId' => trim($childId),
    //                 ]);
    //             }
    //         }

    //         $manager = new ImageManager(new GdDriver());

    //         foreach ($request->file('media') as $file) {
    //             if ($file->isValid()) {
    //                 $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //                 $destinationPath = public_path('uploads/Observation');

    //                 if (!file_exists($destinationPath)) {
    //                     mkdir($destinationPath, 0777, true);
    //                 }

    //                 if ($file->getSize() > 1024 * 1024) {
    //                     $image = $manager->read($file->getPathname());
    //                     $image->scale(1920);
    //                     $image->save($destinationPath . '/' . $filename, 75);
    //                 } else {
    //                     $file->move($destinationPath, $filename);
    //                 }

    //                 ObservationMedia::create([
    //                     'observationId' => $observationId,
    //                     'mediaUrl' => 'uploads/Observation/' . $filename,
    //                     'mediaType' => $file->getClientMimeType(),
    //                 ]);
    //             }
    //         }

    //         DB::commit();

    //         return response()->json(['status' => 'success', 'message' => 'Observation saved successfully.','id' => $observationId]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Observation Store Failed: ' . $e->getMessage());

    //         return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
    //     }
    // }

public function storeTitle(Request $request) {
    $request->validate([
        'obestitle' => 'required'
    ]);

    // dd($request->all());

    $centerid = Session('user_center_id');
    
    try {
        $Observation = new Observation();
        $Observation->obestitle = $request->obestitle;
        $Observation->centerid = $centerid;
        $Observation->userId = Auth::user()->userid;
        $Observation->save();
        
        // dd($Observation->id); // Comment this out!
        
        return redirect()->route('observation.addnew.optional', [
            'id' => $Observation->id,
            'tab' => 'observation',
            'tab2' => 'MONTESSORI'
        ])->with('success', 'Observation created successfully.');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}



public function autosaveobservation(Request $request)
{
    // Custom validation with fallback
    $validator = Validator::make($request->all(), [
        'obestitle'      => 'required',
        'title'          => 'nullable',
        'notes'          => 'nullable',
        'reflection'     => 'nullable',
        'child_voice'    => 'nullable',
        'future_plan'    => 'nullable',
        'observation_id' => 'required|integer',
    ]);

    if ($validator->fails()) {
        // Return JSON with validation errors instead of default 422
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $validator->errors()
        ], 400);
    }

    try {
        // Find existing observation
        $observation = Observation::find($request->observation_id);

        if (!$observation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Observation not found.'
            ], 404);
        }

        // Update fields
        $observation->obestitle   = $request->obestitle;
        $observation->title       = $request->title;
        $observation->notes       = $request->notes;
        $observation->reflection  = $request->reflection;
        $observation->child_voice = $request->child_voice;
        $observation->future_plan = $request->future_plan;

        $observation->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Observation autosaved successfully.',
            'observation_id' => $observation->id
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}



    public function store(Request $request)
    {

        $uploadMaxSize = min(
            $this->convertToBytes(ini_get('upload_max_filesize')),
            $this->convertToBytes(ini_get('post_max_size'))
        );

        $isEdit = $request->filled('id');

        $rules = [
            'selected_rooms'    => 'required',
            'obestitle'         => 'required|string',
            'title'             => 'nullable|string',
            'notes'             => 'nullable|string',
            'reflection'        => 'nullable|string',
            'child_voice'       => 'nullable|string',
            'future_plan'       => 'nullable|string',
            'selected_children' => 'required|string',
            'selected_staff' => 'nullable|string'
        ];




        if (!$isEdit) {
            $rules['media'] = 'nullable|array|min:1';
        } else {
            $rules['media'] = 'nullable|array';
        }

        $rules['media.*'] = "file|mimes:jpeg,png,jpg,gif,webp,mp4|max:" . intval($uploadMaxSize / 1024);

        $messages = [
            'media.required' => 'At least one media file is required.',
            'media.*.max' => 'Each file must be smaller than ' . ($uploadMaxSize / 1024 / 1024) . 'MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $taggedStaff = "";
        if(!empty($request->selected_staff)){
            $taggedStaff = $request->selected_staff;
        }

        DB::beginTransaction();

        try {
            $authId = Auth::user()->id;
            $centerid = Session('user_center_id');

            $observation = $isEdit
                ? Observation::findOrFail($request->id)
                : new Observation();

            $observation->room         = $request->input('selected_rooms');
            $observation->obestitle    = $request->input('obestitle');
            $observation->title        = $request->input('title');
            $observation->notes        = $request->input('notes');
            $observation->reflection   = $request->input('reflection');
            $observation->child_voice  = $request->input('child_voice');
            $observation->future_plan  = $request->input('future_plan');
            $observation->tagged_staff = $taggedStaff;
            if (!$isEdit) {
                $observation->userId   = $authId; // Only set when creating
            }
            $observation->centerid     = $centerid;
            $observation->save();

            $observationId = $observation->id;

            // Replace all existing ObservationChild records
            ObservationChild::where('observationId', $observationId)->delete();

            $selectedChildren = explode(',', $request->input('selected_children'));
            foreach ($selectedChildren as $childId) {
                if (trim($childId) !== '') {
                    ObservationChild::create([
                        'observationId' => $observationId,
                        'childId' => trim($childId),
                    ]);
                }
            }

            ObservationStaff::where('observationId', $observationId)->delete();

            $selectedStaff = explode(',', $request->input('selected_staff'));
            foreach ($selectedStaff as $userid) {
                if (trim($userid) !== '') {
                    ObservationStaff::create([
                        'observationId' => $observationId,
                        'userid' => trim($userid),
                    ]);
                }
            }


            // Process uploaded media only if present
            if ($request->hasFile('media')) {
                $manager = new ImageManager(new GdDriver());

                foreach ($request->file('media') as $file) {
                    if ($file->isValid()) {
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('uploads/Observation');

                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0777, true);
                        }

                        if (Str::startsWith($file->getMimeType(), 'image') && $file->getSize() > 1024 * 1024) {
                            $image = $manager->read($file->getPathname());
                            $image->scale(1920);
                            $image->save($destinationPath . '/' . $filename, 75);
                        } else {
                            $file->move($destinationPath, $filename);
                        }

                        ObservationMedia::create([
                            'observationId' => $observationId,
                            'mediaUrl' => 'uploads/Observation/' . $filename,
                            'mediaType' => $file->getClientMimeType(),
                        ]);
                    }
                }
            }

            DB::commit();


            $selectedChildren = explode(',', $request->input('selected_children'));

            foreach ($selectedChildren as $childId) {
                $childId = trim($childId);
                if ($childId !== '') {
                    // Get all related parent entries for this child
                    $parentRelations = Childparent::where('childid', $childId)->get();

                    foreach ($parentRelations as $relation) {
                        $parentUser = User::find($relation->parentid); // assuming users table stores parent records

                        if ($parentUser) {
                            $parentUser->notify(new ObservationAdded($observation));
                        }
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => $isEdit ? 'Observation updated successfully.' : 'Observation saved successfully.',
                'id' => $observationId
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Observation Store/Update Failed: ' . $e->getMessage());

            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }


    // Helper to convert ini values to bytes
    private function convertToBytes($value)
    {
        $unit = strtolower(substr($value, -1));
        $bytes = (int) $value;

        switch ($unit) {
            case 'g':
                $bytes *= 1024 * 1024 * 1024;
                break;
            case 'm':
                $bytes *= 1024 * 1024;
                break;
            case 'k':
                $bytes *= 1024;
                break;
        }

        return $bytes;
    }



    public function destroyimage($id)
    {
        $media = ObservationMedia::findOrFail($id);

        // Optionally delete the file from storage
        if (file_exists(public_path($media->mediaUrl))) {
            @unlink(public_path($media->mediaUrl));
        }

        $media->delete();

        return response()->json(['status' => 'success']);
    }

    public function print($id)
    {
        // dd($id);
        try {
            // Fetch the observation with all related data using the same relationships as view method
            $observation = Observation::with([
                'media',
                'child.child',
                'montessoriLinks.subActivity.activity.subject',
                'eylfLinks.subActivity.activity.outcome',
                'devMilestoneSubs.devMilestone.main',
                'devMilestoneSubs.devMilestone.milestone',
                'user'  // Assuming you have a room relationship
            ])->findOrFail($id);


            // Only track if the user is authenticated and is a Parent
            $user = Auth::user();
            if ($user && $user->userType === 'Parent') {
                // Check if already seen
                $alreadySeen = SeenObservation::where('user_id', $user->id)
                    ->where('observation_id', $id)
                    ->exists();

                if (! $alreadySeen) {
                    SeenObservation::create([
                        'user_id' => $user->id,
                        'observation_id' => $id
                    ]);
                }
            }


            $roomNames = Room::whereIn('id', explode(',', $observation->room))
                ->pluck('name')
                ->implode(', '); // or ->toArray() if you prefer array

            return view('observations.print', compact('observation', 'roomNames'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Observation not found or error occurred while loading the print view.');
        }
    }





    public function snapshotindex()
    {

        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        if (Auth::user()->userType == "Superadmin") {
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }

        if (Auth::user()->userType == "Superadmin") {

            $snapshots = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                ->where('centerid', $centerid)
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page

        } elseif (Auth::user()->userType == "Staff") {

            $snapshots = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                ->where('createdBy', $authId)
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page

        } else {

            $childids = Childparent::where('parentid', $authId)->pluck('childid');
            $snapshotid = SnapshotChild::whereIn('childid', $childids)
                ->pluck('snapshotid')
                ->unique()
                ->toArray();
            // dd($childids);
            $snapshots = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                ->whereIn('id', $snapshotid)
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page

        }


        $allRoomIds = $snapshots->pluck('roomids')
            ->flatMap(function ($roomids) {
                return explode(',', $roomids);
            })
            ->unique()
            ->filter(); // Use filter to remove any empty values


        $rooms = collect();
        if ($allRoomIds->isNotEmpty()) {
            $rooms = Room::whereIn('id', $allRoomIds)->get()->keyBy('id');
        }


        $snapshots->each(function ($snapshot) use ($rooms) {
            $roomIds = explode(',', $snapshot->roomids);
            $snapshot->rooms = $rooms->whereIn('id', $roomIds)->values();
        });

       $permissions = Permission::where('userid', Auth::user()->userid)->first();

        //  dd($snapshots);

        return view('observations.snapshotindex', compact('snapshots', 'centers','permissions'));
    }



    public function snapshotindexstorepage($id = null)
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        $reflection = null;
        if ($id) {
            $reflection = Snapshot::with(['creator', 'center', 'children', 'media'])->find($id);
        }

        $childrens = $reflection
            ? $reflection->children->pluck('child')->filter()
            : collect();

        $rooms = collect();

        if ($reflection && $reflection->roomids) {
            $roomIds = explode(',', $reflection->roomids); // Convert comma-separated string to array
            $rooms = Room::whereIn('id', $roomIds)->get();
        }
$educators = collect();
        if($reflection && $reflection->educators){
  $educatorsIds = explode(',', $reflection->educators); // Convert comma-separated string to array
            $educators = User::whereIn('userid', $educatorsIds)->get();
        }

        //     $staffs = $reflection
        // ? $reflection->staff->pluck('staff')->filter()
        // : collect();

        $outcomes = EYLFOutcome::with('activities.subActivities')->get();

        // $Usercenters = Usercenter::where('centerid',$centerid)->pluck('userid');
        // $educators = User::where('userType','Staff')->whereIn('userid',$Usercenters)->where('status','ACTIVE')->get();
        // dd( $educators);





        return view('observations.storesnapshots', compact('reflection', 'childrens', 'rooms', 'outcomes','educators'));
    }




public function snapshotstore(Request $request)
{
    $uploadMaxSize = min(
        $this->convertToBytes(ini_get('upload_max_filesize')),
        $this->convertToBytes(ini_get('post_max_size'))
    );

    $isEdit = $request->filled('id');

    // Validation rules
    $rules = [
        'selected_rooms'    => 'required',
        'title'             => 'required|string',
        'about'             => 'required|string',
        'selected_children' => 'required|string',
        'selected_staff'    => 'required|string'
    ];

    if (!$isEdit) {
        $rules['media'] = 'required|array|min:1';
    } else {
        $rules['media'] = 'nullable|array';
    }

    $rules['media.*'] = "file|mimes:jpeg,png,jpg,gif,webp,mp4|max:" . intval($uploadMaxSize / 1024);

    $messages = [
        'media.required' => 'At least one media file is required.',
        'media.*.max' => 'Each file must be smaller than ' . ($uploadMaxSize / 1024 / 1024) . 'MB.',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation failed',
            'errors'  => $validator->errors(),
        ], 422);
    }

    $validated = $validator->validated();

    DB::beginTransaction();

    try {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        $snapshot = $isEdit
            ? Snapshot::findOrFail($request->id)
            : Snapshot::create([
                'title'     => $validated['title'],
                'about'     => $validated['about'],
                'centerid'  => $centerid,
                'createdBy' => $authId,
                'educators' => $validated['selected_staff'],
            ]);

        // Update roomids after creation if new snapshot
        if (!$isEdit) {
            $snapshot->roomids = $validated['selected_rooms'];
            $snapshot->save();
        } else {
            $snapshot->roomids   = $validated['selected_rooms'];
            $snapshot->title     = $validated['title'];
            $snapshot->about     = $validated['about'];
            $snapshot->centerid  = $centerid;
            $snapshot->createdBy = $authId;
            $snapshot->educators = $validated['selected_staff'];
            $snapshot->save();
        }

        $snapshotId = $snapshot->id;

        // Replace children
        SnapshotChild::where('snapshotid', $snapshotId)->delete();
        $selectedChildren = explode(',', $validated['selected_children']);
        foreach ($selectedChildren as $childId) {
            if (trim($childId) !== '') {
                SnapshotChild::create([
                    'snapshotid' => $snapshotId,
                    'childid'    => trim($childId),
                ]);
            }
        }

        // Handle media
        if ($request->hasFile('media')) {
            $manager = new ImageManager(new GdDriver());
            $destinationPath = public_path('uploads/Snapshots');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            foreach ($request->file('media') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                    if (Str::startsWith($file->getMimeType(), 'image') && $file->getSize() > 1024 * 1024) {
                        $image = $manager->read($file->getPathname());
                        $image->scale(1920);
                        $image->save($destinationPath . '/' . $filename, 75);
                    } else {
                        $file->move($destinationPath, $filename);
                    }

                    SnapshotMedia::create([
                        'snapshotid' => $snapshotId,
                        'mediaUrl'   => 'uploads/Snapshots/' . $filename,
                        'mediaType'  => $file->getClientMimeType(),
                    ]);
                }
            }
        }

        DB::commit();

        return response()->json([
            'status'  => true,
            'message' => $isEdit ? 'Snapshot updated successfully.' : 'Snapshot saved successfully.',
            'id'      => $snapshotId
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong. Please try again later.',
            'error'   => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}


public function viewSnapShot($id)
{
    // Fetch snapshot or fail gracefully
    $snapshots = Snapshot::findOrFail($id);

    // Get children linked to this snapshot
    $snapchildren = SnapshotChild::where('snapshotid', $id)->pluck('childid');
    $childrens = Child::whereIn('id', $snapchildren)->get();
    // dd( $childrens);

    // Get educators (stored as CSV in snapshot->educators)
    $snapeducators = $snapshots->educators;
    $educators = collect(); // default empty

    if (!empty($snapeducators)) {
        $educatorsarray = explode(',', $snapeducators);
        $educators = User::whereIn('userid', $educatorsarray)->get();
    }

     $snaprooms = $snapshots->roomids;
    //  dd($snaprooms );
    $rooms = collect(); // default empty

    if (!empty($snaprooms)) {
        $roomsarray = explode(',', $snaprooms);
        $rooms = Room::whereIn('id', $roomsarray)->get();
    }


    // Get media files linked to snapshot
    $snapmedia = SnapshotMedia::where('snapshotid', $id)->get();

    return view('observations.viewsnapshot', compact(
        'childrens',
        'snapshots',
        'educators',
        'snapmedia',
        'rooms'
    ));
}



    public function snapshotdestroyimage($id)
    {
        $media = SnapshotMedia::findOrFail($id);

        // Optionally delete the file from storage
        if (file_exists(public_path($media->mediaUrl))) {
            @unlink(public_path($media->mediaUrl));
        }

        $media->delete();

        return response()->json(['status' => 'success']);
    }

    public function snapshotupdateStatus(Request $request)
    {
        $request->validate([
            'reflectionId' => 'required|exists:snapshot,id',
            'status' => 'required|in:Published,Draft'
        ]);

        $reflection = Snapshot::find($request->reflectionId);
        $reflection->status = $request->status;
        $reflection->save();

        return response()->json(['status' => 'success', 'id' => $reflection->id]);
    }


    public function snapshotsdelete($id)
    {
        $snapshot = Snapshot::findOrFail($id);
        $snapshot->delete();

        return response()->json(['status' => 'success']);
    }
    

    public function changeCreatedAt(Request $request)
    {
        $obs = Observation::find($request->id);
        if(!$obs) return response()->json(['success' => false, 'message' => 'Observation not found']);

        $newDate = \Carbon\Carbon::parse($request->created_at)->addDay();

        $obs->created_at = $newDate;
        $obs->save();

        return response()->json(['success' => true]);
    }

    public function commentstore(Request $request, Observation $observation) {
        $validated = $request->validate([
            'comments' => 'required|string|max:1000'
        ]);
        $comment = $observation->comments()->create([
            'userId' => auth()->id(),
            'comments' => $validated['comments'],
        ]);
        return response()->json([
            'success' => true,
            'comment' => [
                'comments' => $comment->comments,
                'user_name' => $comment->user->name,
                'created_at' => $comment->created_at->diffForHumans()
            ]
        ]);
    }

    public function destroycomment(Request $request, ObservationComment $comment)
    {
        $user = $request->user();

        // Authorization: superadmin or owner of comment
        if ($user->userType === 'Superadmin' || $user->id === $comment->userId) {
            $comment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized to delete this comment'
        ], 403);
    }


    public function destroy($id)
    {
        try {
            // Find the observation by ID
            $observation = Observation::findOrFail($id);
            
            // Delete the observation
            $observation->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Observation deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting observation. Please try again.'
            ], 500);
        }
    }

    
}
