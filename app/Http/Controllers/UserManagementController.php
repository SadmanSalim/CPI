<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::all(); 
        return response()->json($users);
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'department' => 'nullable|string',
            'graduationYear' => 'nullable|string',
            'registration' => 'nullable|string',
            'roll' => 'nullable|string',
        ]);

        $user->update($request->only('name', 'department', 'graduationYear', 'registration', 'roll'));

        return response()->json(['message' => 'User updated successfully.']);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|in:1,2', // 1 = Admin, 2 = User
        ]);
    
        $data = [
            'role_id' => $request->role_id,
        ];
    
        if ($request->role_id == 1) {
            $data['role'] = 'admin';
        } else {
            $data['role'] = 'user';
        }
    
        $user->update($data);
    
        return response()->json(['message' => 'Role updated successfully.']);
    }
    
    

    public function resetPassword(Request $request, User $user)
    {
        $newPassword = '#P@sSw$ord1*/3'; 
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return response()->json([
            'message' => 'Password reset successfully.',
            'new_password' => $newPassword,
        ]);
    }
}
