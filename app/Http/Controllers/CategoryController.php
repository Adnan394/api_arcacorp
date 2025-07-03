<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $data = Category::withTrashed()->get();
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Categories retrieved successfully.'
        ]);
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'limit_per_month' => 'nullable|string',
            ]);

            $category = Category::create([
                'name' => $request->name,
                'limit_per_month' => $request->limit_per_month,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $category,
                'message' => 'Category created successfully.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category.'
            ], 500);
        }
    }
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'limit_per_month' => 'nullable|string',
            ]);

            $category = Category::findOrFail($id);
            $category->update([
                'name' => $request->name,
                'limit_per_month' => $request->limit_per_month,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $category,
                'message' => 'Category updated successfully.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category.'
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $category,
                'message' => 'Category deleted successfully.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category.'
            ], 500);
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $category = Category::withTrashed()->findOrFail($id);
            $category->restore();

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $category,
                'message' => 'Category restored successfully.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore category.'
            ], 500);
        }
    }
}