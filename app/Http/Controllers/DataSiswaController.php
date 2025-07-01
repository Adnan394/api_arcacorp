<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MasterUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class DataSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::with('master_user')->where('role', 'Siswa')->get();
        return view('data_siswa.index', [
            'data' => $data,
            'active' => 'data_siswa'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('data_siswa.create', [
            'active' => 'data_siswa'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $filename = "";
            if($request->hasFile('foto')) {
                $fotoFile = $request->file('foto');
                $fotoFile->move(public_path('img/siswa'));
                $filename = 'img/siswa/'. $fotoFile->getClientOriginalName();
            }
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make("user12345"),
                'role' => 'Siswa'
            ]);

            MasterUser::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->nama_lengkap,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'foto' => $filename
            ]);

            DB::commit();
            return redirect()->route('data_siswa.index')->with('success', 'Data Siswa Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('data_siswa.index')->with('error', 'Data Siswa Gagal Ditambahkan! Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::with('master_user')->where('id', $id)->first();
        return view('data_siswa.edit', [
            'data' => $data,
            'active' => 'data_siswa'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $filename = "";
            if($request->hasFile('foto')) {
                $fotoFile = $request->file('foto');
                $fotoFile->move(public_path('img/siswa'));
                $filename = 'img/siswa/'. $fotoFile->getClientOriginalName();
            }
            $user = User::find($id);
            $user->update([
                'username' => $request->username,
                'email' => $request->email
            ]);

            $masterUser = MasterUser::where('user_id', $id)->first();
            $masterUser->update([
                'nama_lengkap' => $request->nama_lengkap,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'foto' => $filename
            ]);

            DB::commit();
            return redirect()->route('data_siswa.index')->with('success', 'Data Siswa Berhasil Diubah!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('data_siswa.index')->with('error', 'Data Siswa Gagal Diubah! Error: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->delete();
            DB::commit();
            return redirect()->route('data_siswa.index')->with('success', 'Data Siswa Berhasil Dihapus!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('data_siswa.index')->with('error', 'Data Siswa Gagal Dihapus! Error: ' . $th->getMessage());
        }
    }
}