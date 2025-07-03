<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => \App\Models\User::with('role')->withTrashed()->get(),
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|',
                'role_id' => 'required|exists:roles,id',
            ]);

            \App\Models\User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => $request->role_id,
            ]);
            DB::commit();
            return response()->json(['message' => 'User created successfully.'], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create user.'], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8',
                'role_id' => 'required|exists:roles,id',
            ]);

            $user = \App\Models\User::findOrFail($id);
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($user->password); // Keep the old password if not updated
            $user->role_id = $request->role_id;

            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            $user->save();
            DB::commit();
            return response()->json(['message' => 'User updated successfully.'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update user.'], 500);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $user = \App\Models\User::findOrFail($id);
            $user->delete();
            DB::commit();
            return response()->json(['message' => 'User deleted successfully.'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete user.'], 500);
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $user = \App\Models\User::withTrashed()->findOrFail($id);
            $user->restore();
            DB::commit();
            return response()->json(['message' => 'User restored successfully.'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to restore user.'], 500);
        }
    }
}