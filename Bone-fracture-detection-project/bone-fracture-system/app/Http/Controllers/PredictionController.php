<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Laravel\Facades\Image;


class PredictionController extends Controller
{
    public function predict(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            // Get the uploaded image file
            $image = $request->file('image');

            // Make API request to the Python backend
            $response = Http::attach(
                'file',
                file_get_contents($image->getRealPath()),
                $image->getClientOriginalName()
            )->post('http://127.0.0.1:5000/predict');

            if ($response->successful()) {
                $predictions = $response->json();

                if (!isset($predictions['predicted_box'])) {
                    return response()->json([
                        'error' => 'No bounding box returned from the API',
                    ], 500);
                }

                $predictedBox = $predictions['predicted_box'];

                // Load the uploaded image using Intervention/Image
                $imagePath = $image->getRealPath();
                $img = Image::read($imagePath);

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
                // Draw the bounding box on the image
                $img->drawRectangle($x1, $y1, function ($rectangle) use ($x1, $y1, $x2, $y2) {
                    $rectangle->size($x2 - $x1, $y2 - $y1); // Width and height of the rectangle
                    $rectangle->border('#00FF00', 3);      // Correct order: color first, thickness second
                });



            // Save the modified image in the 'temp' folder within 'storage/app'
                $tempFolder = storage_path('app/temp');
                if (!file_exists($tempFolder)) {
                    mkdir($tempFolder, 0777, true);
                }

                $outputFileName = Str::random(10) . '_output.jpg';
                $outputPath = $tempFolder . '/' . $outputFileName;

                $img->save($outputPath);

                // Return the path of the saved image
                return response()->json([
                    'message' => 'Image processed successfully',
                    'image_url' => asset('storage/temp/' . $outputFileName),
                ]);
            } else {
                return response()->json([
                    'error' => 'Failed to get prediction',
                    'details' => $response->body(),
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
