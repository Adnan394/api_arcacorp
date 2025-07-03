<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Role::withTrashed()->get(),
            'message' => 'Roles retrieved successfully.'
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
            ]);

            Role::create($request->only('name'));
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Role created successfully.'
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create role.'
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $role = Role::findOrFail($id);
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            ]);

            $role->update($request->only('name'));
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update role.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete role.'
            ], 500);
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $role = Role::withTrashed()->findOrFail($id);
            $role->restore();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Role restored successfully.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore role.'
            ], 500);
        }
    }
}