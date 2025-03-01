<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function generatePDF(Request $request)
    {
        $data = [
            'patient_name' => $request->input('name'),
            'patient_email' => $request->input('email'),
            'fracture_size' => $request->input('fracture_size'),
            'healing_time' => $request->input('healing_time'),
            'date' => $request->input('date'),
            'feedback' => $request->input('feedback'), // Add the feedback (diagnosis) field
        ];
        Log::info($request->input('image_url'));

        $pdf = PDF::loadView('report-generation.report', $data);

        return $pdf->download('Diagnosis_Report.pdf');
    }



}
