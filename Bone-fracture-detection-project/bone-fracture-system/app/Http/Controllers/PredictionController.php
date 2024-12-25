<?php

namespace App\Http\Controllers;

use App\Services\PredictionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PredictionController extends Controller
{
    protected $predictionService;

    public function __construct(PredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    public function predict(Request $request,$type)
    {
        Log::info($type);
        try {
            $image = $request->file('image');
            if ($type == "classify"){
                return $this->predictionService->classification($image);
            }else{
                $regressionResponse = $this->predictionService->predictRegression($image);
                $responseRegressionData = json_decode($regressionResponse->getContent(), true);

                $classifyResponse = $this->predictionService->classification($image);
                $responseClassifyData = json_decode($classifyResponse->getContent(), true);

                if ($regressionResponse->isSuccessful() && $classifyResponse->isSuccessful()){
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
