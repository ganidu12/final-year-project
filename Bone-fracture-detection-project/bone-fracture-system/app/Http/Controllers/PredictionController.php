<?php

namespace App\Http\Controllers;

use App\Services\PatientHistoryService;
use App\Services\PredictionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PredictionController extends Controller
{
    protected $predictionService;
    protected $patientHistoryService;

    public function __construct(PredictionService $predictionService, PatientHistoryService $patientHistoryService)
    {
        $this->predictionService = $predictionService;
        $this->patientHistoryService = $patientHistoryService;
    }

    public function predict(Request $request)
    {
        Log::info("current user". Auth::user()->email);
        try {
            $image = $request->file('image');
            $classifyResponse = $this->predictionService->classification($image);
            $responseClassifyData = json_decode($classifyResponse->getContent(), true);
            if ($responseClassifyData['image_class'] == 'Non-Fractured'){
                if (Auth::user()->user_type == 'doctor'){
                    $this->patientHistoryService->saveDiagnosis($request,$responseClassifyData['image_class'],Auth::user()->id);
                }
                return $classifyResponse;
            }else{
                $regressionResponse = $this->predictionService->predictRegression($image);
                $responseRegressionData = json_decode($regressionResponse->getContent(), true);

                if ($regressionResponse->isSuccessful() && $classifyResponse->isSuccessful()){
                    if (Auth::user()->user_type == 'doctor'){
                        $this->patientHistoryService->saveDiagnosis($request,$responseClassifyData['image_class'],Auth::user()->id,$responseRegressionData);
                    }
                    return response()->json([
                        'image_url' => $responseRegressionData['image_url'],
                        'diagonal_mm'=> $responseRegressionData['diagonal_mm'],
                        'image_class' => $responseClassifyData['image_class']
                    ]);

                }else{
                    Log::error("An error occurred during classification and regression");
                    return response("An error occurred during classification and regression");
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
