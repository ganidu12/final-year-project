<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use App\Services\ForgotPasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    protected $forgotPasswordService;

    public function __construct(ForgotPasswordService $forgotPasswordService)
    {
        $this->forgotPasswordService = $forgotPasswordService;
    }

    public function index()
    {
        return view('auth.forgot-password');
    }
    public function sendResetCode(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $code = rand(1000, 9999);
        $this->forgotPasswordService->resetCode($request->email, $code);
        $mailable = new ForgotPasswordMail($code);

        Mail::to($request->email)->send($mailable);

        return response()->json([
            'success' => true,
            'message' => 'A verification code has been sent to your email.'
        ]);

    }

    public function verifyResetCode(Request $request)
    {
        $request->validate(['code' => 'required']);

        $user = User::where('reset_code', $request->code)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid verification code!']);
        }

        return response()->json(['success' => true, 'message' => 'Code verified!']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'new_password' => 'required|min:6'
        ]);
        $this->forgotPasswordService->resetPassword($request->email,$request->new_password);
        return response()->json(['success' => true, 'message' => 'Password has been reset successfully!']);
    }
}

