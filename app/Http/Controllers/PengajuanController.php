<?php

namespace App\Http\Controllers;

use App\Models\Log_activity;
use Illuminate\Http\Request;
use App\Mail\KirimNotifikasiEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Container\Attributes\Log;

class PengajuanController extends Controller
{
    public function index(Request $request) {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Pengajuan::where('user_id', $request->user()->id)->get(),
        ]);
    }
    public function getAll(Request $request) {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Pengajuan::withTrashed()->get(),
        ]);
    }

    public function approve(Request $request, $id) {
        $pengajuan = \App\Models\Pengajuan::findOrFail($id);
        $pengajuan->update(['status' => 'approved', 'approved_at' => now()]);

        Log_activity::create([
            'activity' => 'Approved',
            'user_id' => $request->user()->id,
            'pengajuan_id' => $pengajuan->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan approved successfully',
        ]);
    }

    public function reject(Request $request, $id) {
        $pengajuan = \App\Models\Pengajuan::findOrFail($id);
        $pengajuan->update(['status' => 'rejected']);

        Log_activity::create([
            'activity' => 'Rejected',
            'user_id' => $request->user()->id,
            'pengajuan_id' => $pengajuan->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan rejected successfully',
        ]);
    }

    public function store(Request $request) {
       $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();
            $file = $request->file('file');
            $path = null;
            if ($file) {
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('img/bukti'), $fileName);
                $path = 'img/bukti/' . $fileName;
            }

            $category = \App\Models\Category::find($request->category_id);
            if ($category->limit_per_month <= \App\Models\Pengajuan::where('user_id', $request->user()->id)->whereMonth('created_at', now()->format('m'))->sum('amount') + $request->amount) {
                return response()->json([
                    'success' => false,
                    'message' => "Limit per month for category $category->name has been exceeded. You have used Rp. " . number_format(\App\Models\Pengajuan::where('user_id', $request->user()->id)->whereMonth('created_at', now()->format('m'))->sum('amount'), 0, ',', '.') . " and the remaining limit is Rp. " . number_format($category->limit_per_month - \App\Models\Pengajuan::where('user_id', $request->user()->id)->whereMonth('created_at', now()->format('m'))->sum('amount'), 0, ',', '.'),
                ], 422);
            }else {
                $pengajuan = \App\Models\Pengajuan::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'category_id' => $request->category_id,
                    'amount' => $request->amount,
                    'file' => $path,
                    'submited_at' => now(),
                    'user_id' => $request->user()->id,
                ]);
    
                Log_activity::create([
                    'activity' => 'Pengajuan Baru',
                    'user_id' => $request->user()->id,
                    'pengajuan_id' => $pengajuan->id,
                ]);

                Mail::to($request->user()->email)->queue(new KirimNotifikasiEmail($request->user()->name, $category->name, $request->amount));
                DB::commit();
    
                return response()->json([
                    'success' => true,
                    'data' => $pengajuan,
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        } 
    }

    public function destroy(Request $request, $id) {
        $pengajuan = \App\Models\Pengajuan::findOrFail($id);
        $pengajuan->update(['status' => 'cancelled']);
        $pengajuan->delete();

        Log_activity::create([
            'activity' => 'Status Berubah',
            'user_id' => $request->user()->id,
            'pengajuan_id' => $pengajuan->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan deleted successfully',
        ]);
    }
}