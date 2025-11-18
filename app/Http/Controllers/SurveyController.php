<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function list(Request $request)
{
    if (Session::has('LoginId')) {
        $centerid = $request->query('centerid');

        if (!$centerid) {
            $center = Session::get('centerIds');
            $centerid = $center[0]->id ?? null;
        }

        $data = [
            'centerid' => $centerid,
            'userid' => Session::get('LoginId'),
        ];

        $response = Http::withHeaders([
            'X-Device-Id' => Session::get('X-Device-Id'),
            'X-Token'     => Session::get('AuthToken'),
        ])->post(env('BASE_API_URL') . 'surveys/surveysList/', $data);

        if ($response->successful()) {
            $jsonOutput = $response->json();
            $jsonOutput['centerid'] = $centerid;
            return view('surveysList_v3', $jsonOutput);
        }

        if ($response->status() === 401) {
            return redirect('welcome');
        }
    }

    return redirect('welcome');
}
}
