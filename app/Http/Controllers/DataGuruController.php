<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MasterUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class DataGuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::with('master_user')->where('role', 'Guru')->get();
        return view('data_guru.index', [
            'data' => $data,
            'active' => 'data_guru'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('data_guru.create', [
            'active' => 'data_guru'
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
                $fotoFile->move(public_path('img/guru'));
                $filename = 'img/guru/'. $fotoFile->getClientOriginalName();
            }
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make("user12345"),
                'role' => 'Guru',
                'deskripsi' => $request->deskripsi
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
            return redirect()->route('data_guru.index')->with('success', 'Data Guru Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('data_guru.index')->with('error', 'Data Guru Gagal Ditambahkan! Error: ' . $e->getMessage());
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
        return view('data_guru.edit', [
            'data' => $data,
            'active' => 'data_guru'
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
                $fotoFile->move(public_path('img/guru'));
                $filename = 'img/guru/'. $fotoFile->getClientOriginalName();
            }
            $user = User::find($id);
            $user->update([
                'username' => $request->username,
                'email' => $request->email,
                'deskripsi' => $request->deskripsi
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
            return redirect()->route('data_guru.index')->with('success', 'Data Guru Berhasil Diubah!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('data_guru.index')->with('error', 'Data Guru Gagal Diubah! Error: ' . $th->getMessage());
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
            return redirect()->route('data_guru.index')->with('success', 'Data Guru Berhasil Dihapus!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('data_guru.index')->with('error', 'Data Guru Gagal Dihapus! Error: ' . $th->getMessage());
        }
    }
}