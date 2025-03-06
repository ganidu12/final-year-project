<?php

namespace App\Http\Controllers;

use App\Services\emailSenderService;
use App\Services\PatientHistoryService;
use App\Services\PredictionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PredictionController extends Controller
{
    protected $predictionService;
    protected $patientHistoryService;
    protected $emailSenderService;

    public function __construct(PredictionService $predictionService, PatientHistoryService $patientHistoryService, EmailSenderService $emailSenderService)
    {
        $this->predictionService = $predictionService;
        $this->patientHistoryService = $patientHistoryService;
        $this->emailSenderService = $emailSenderService;
    }

    public function index()
    {
        Log::info('Authenticated User:', ['user' => Auth::user()]);
        return view('analyze');
    }

    public function predict(Request $request)
    {
        Log::info($request);
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $validator->sometimes('patientEmail', 'required|email|max:255', function () {
            return Auth::user()->user_type === 'doctor';
        });
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
        Log::info("current user". Auth::user()->email);
        try {
            $image = $request->file('image');
            $isNotRegistered = filter_var($request->isNotRegistered, FILTER_VALIDATE_BOOLEAN);
            Log::info("IS NOT REGISTERED" . $isNotRegistered);
            $classifyResponse = $this->predictionService->classification($image);
            $responseClassifyData = json_decode($classifyResponse->getContent(), true);
            if ($responseClassifyData['image_class'] == 'Non-Fractured'){
                return $classifyResponse;
            }else{
                $regressionResponse = $this->predictionService->predictRegression($image);
                $responseRegressionData = json_decode($regressionResponse->getContent(), true);
                if ($regressionResponse->isSuccessful() && $classifyResponse->isSuccessful()){
                    if (Auth::user()->user_type == 'doctor'){
                        $healingTime = $this->predictionService->healing_time($responseRegressionData['diagonal_mm'],$request);
                        if ($isNotRegistered){
                            Log::info("IS NOT REGISTERED");
                            $this->emailSenderService->sendPatientResults($request,$responseClassifyData['image_class'],Auth::user()->name,$responseRegressionData,$healingTime);
                            $this->patientHistoryService->saveDiagnosis($request,$responseClassifyData['image_class'],Auth::user()->id,$responseRegressionData,$healingTime,$isNotRegistered);
                        }else{
                            $this->patientHistoryService->saveDiagnosis($request,$responseClassifyData['image_class'],Auth::user()->id,$responseRegressionData,$healingTime,$isNotRegistered);
                        }

                    }else{
                        $healingTime = $this->predictionService->healing_time($responseRegressionData['diagonal_mm']);
                    }
                    return response()->json([
                        'image_url' => $responseRegressionData['image_url'],
                        'diagonal_mm'=> round($responseRegressionData['diagonal_mm'], 2),
                        'image_class' => $responseClassifyData['image_class'],
                        'healing_time' => $healingTime
                    ]);

                }else{
                    Log::error("An error occurred during classification and regression");
                    return response("An error occurred during classification and regression");
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'error' => 'An error occurred',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
