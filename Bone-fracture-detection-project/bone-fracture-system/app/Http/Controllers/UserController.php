<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function profileIndex()
    {
        return view('profile');
    }
    public function updateProfile(Request $request)
    {
        Log::info($request);
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:255',
                'profile_img' => 'nullable|image|max:2048',
            ];
            if (auth()->user()->user_type === 'regular_user') {
                $rules['age'] = 'required|integer|min:1|max:120';
            }
            $request->validate($rules);

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->getMessage(),
            ], 422);
        }

        $user = $this->userService->updateUser(auth()->id(), $request->all());
        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'profile_img_url' => isset($data['profile_img']) ? asset('storage/' . $data['profile_img']) : null,
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Failed to update profile',
        ], 500);
    }

    public function fetchPatientDetailsWithEmail(Request $request){
        $search = $request->get('query');
        $patients = $this->userService->fetchUserDetails($search,'email');
        return response()->json($patients);
    }

    public function fetchPatientDetailsWithName(Request $request){
        $search = $request->get('query');
        $patients = $this->userService->fetchUserDetails($search,'name');
        return response()->json($patients);
    }

}
