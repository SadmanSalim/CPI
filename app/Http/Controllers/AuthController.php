<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PreapprovedUser;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'graduation_batch' => 'required',
            'department' => 'required',
            'user_type' => 'required|string'
        ]);
    
        if ($request->user_type === 'alumni') {
            $request->validate([
                'jobTitle' => 'required',
                'company' => 'required',
                'location' => 'required',
            ]);
        }
    
        $user = User::create([
            'name' => $request->name,
            'user_type' => $request->user_type ?? 'student',
            'student_id' => $request->student_id ?? null,
            'email' => $request->email,
            'graduation_batch' => $request->graduation_batch,
            'department' => $request->department,
            'bio' => $request->bio ?? null,
            'password' => Hash::make($request->password),
            'role_id' => 2,
        ]);
    
        if ($request->user_type === 'alumni') {
            $user->occupation()->create([
                'current_job_title' => $request->jobTitle,
                'current_job_company' => $request->company,
                'current_job_location' => $request->location,
            ]);
        }
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'message' => 'Registration successful',
            'token' => $token,
            'user' => $user
        ]);
    }
    
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }


    return response()->json([
        'token' => $user->createToken('auth_token')->plainTextToken,
        'user' => $user,
    ]);
}

public function logout(Request $request){
    $user = $request->user;
    $user->tokens()->delete();

    return response()->json([
        'status' => true,
        'user' => $user,
        'message' => 'You logged Out Successfully',
    ], 200);
}


}
