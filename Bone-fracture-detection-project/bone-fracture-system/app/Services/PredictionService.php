<?php
namespace App\Services;

use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class PredictionService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function predictRegression($image)
    {
        $response = Http::attach(
            'file',
            file_get_contents($image->getRealPath()),
            $image->getClientOriginalName()
        )->post('http://127.0.0.1:5000/regression');
        if ($response->successful()) {
            $predictions = $response->json();

            if (!isset($predictions['predicted_box'])) {
                return response()->json([
                    'error' => 'No bounding box returned from the API',
                ], 500);
            }

            $predictedBox = $predictions['predicted_box'];
            $imagePath = $image->getRealPath();
            $img = Image::read($imagePath);

            $dpiInfo = $img->resolution() ?? '300 x 300';
            $dpiParts = explode('x', $dpiInfo);
            $dpi = (float)trim($dpiParts[0]);

            Log::info('Extracted DPI: ' . $dpi);
            $pixelToMmRatio = 25.4 / $dpi;
            Log::info('Pixel-to-mm Ratio: ' . $pixelToMmRatio);

            // Get image dimensions
            $imgWidth = $img->width();
            $imgHeight = $img->height();

            $x1 = $predictedBox[0] * $imgWidth; // x1 normalized to pixels
            $y1 = $predictedBox[1] * $imgHeight; // y1 normalized to pixels
            $x2 = $predictedBox[2] * $imgWidth; // x2 normalized to pixels
            $y2 = $predictedBox[3] * $imgHeight; // y2 normalized to pixels

            Log::info($x1);
            Log::info($y1);
            Log::info($x2);
            Log::info($y2);

            $widthPixels = $x2 - $x1;
            $heightPixels = $y2 - $y1;

            // Convert pixel dimensions to mm
            $widthMm = $widthPixels * $pixelToMmRatio;
            $heightMm = $heightPixels * $pixelToMmRatio;
            $diagonalMm = sqrt(pow($widthMm, 2) + pow($heightMm, 2)); // Diagonal in mm

            // Log real-world dimensions
            Log::info('DPI: ' . $dpi);
            Log::info('Pixel-to-mm Ratio: ' . $pixelToMmRatio);
            Log::info('Diagonal (mm): ' . $diagonalMm);

            $img->drawRectangle($x1, $y1, function ($rectangle) use ($x1, $y1, $x2, $y2) {
                $rectangle->size($x2 - $x1, $y2 - $y1);
                $rectangle->border('#00FF00', 3);
            });

            $tempFolder = storage_path('app/public/temp');
            if (!file_exists($tempFolder)) {
                mkdir($tempFolder, 0777, true);
            }

            $outputFileName = Str::random(10) . '_output.jpg';
            $outputPath = $tempFolder . '/' . $outputFileName;

            $img->save($outputPath);


            return response()->json([
                'message' => 'Image processed successfully',
                'image_url' => asset('storage/temp/' . $outputFileName),
                'diagonal_mm' => $diagonalMm,
            ]);
        }
        else {
            return response()->json([
                'error' => 'Failed to get prediction',
                'details' => $response->body(),
            ], $response->status());
        }
    }


    public function classification($image)
    {
        $response = Http::attach(
            'file',
            file_get_contents($image->getRealPath()),
            $image->getClientOriginalName()
        )->post('http://127.0.0.1:5000/classify');

        if ($response->successful()) {
            $predictions = $response->json();
            Log::info($predictions);
            $predictedClass = $predictions['predicted_class'];
            Log::info("llll ".$predictedClass);
            return response()->json([
                'message' => 'Image processed successfully',
                'image_class' => $predictedClass,
            ]);
        } else {
            Log::info("NOT FOUND");
            return response()->json([
                'error' => 'Failed to get prediction',
                'details' => $response->body(),
            ], $response->status());
        }
    }

    public function healing_time($fracture_size,$request= null)
    {
        if ($request){
            $age = $request->patientAge;
        }else{
            $age = Auth::user()->age;
        }
        Log::info("AGE ".$age);

        $fracture_size_cm = $fracture_size / 10;

        $base_healing_time = 6;

        $healing_time = $base_healing_time + ($fracture_size_cm * ($age / 100));

        $min_weeks = floor($healing_time);
        $max_weeks = ceil($healing_time);

        return "{$min_weeks}-{$max_weeks} weeks";
    }

}
