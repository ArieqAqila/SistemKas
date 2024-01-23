<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $petugas = User::where('hak_akses', 'petugas')->get();
        return view('admin/pages/data-petugas', compact('petugas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inNamaPetugas' => 'required|max:35',
            'inUsernamePetugas' => 'required|unique:users,username|min:6|max:15',
            'inPasswordPetugas' => 'required|min:6|max:12',
            'inNoTelpPetugas' => 'required|min:10|max:15',
            'inAlamatPetugas' => 'required',
            'inFotoPetugas' => 'required|image|max:10000',
            'inTglLahirPetugas' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Petugas gagal ditambahkan!',
                'errors' => $validator->errors(),
            ], 422);
        }

        $petugas = new User;

        if ($request->hasFile('inFotoPetugas')) {
            $path = public_path()."\images\Profile Petugas\\";
            $file = $request->file('inFotoPetugas');

            $image = Image::make($file);
            $image->resize(250, 250);


            $extension = $file->getClientOriginalExtension();
            $filename = md5(time()). '.' .$extension;
            $image->save($path . $filename, 80);

            $petugas->foto_profile = $filename;
        }

        $petugas->nama_user = $request->inNamaPetugas;
        $petugas->username = $request->inUsernamePetugas;
        $petugas->password = hash::make($request->inPasswordPetugas);
        $petugas->notelp = $request->inNoTelpPetugas;
        $petugas->tgl_lahir = $request->inTglLahirPetugas;
        $petugas->alamat = $request->inAlamatPetugas;
        $petugas->hak_akses = 'petugas';
        $petugas->is_first_login = false;
        $petugas->save();

        if ($petugas) {
            return response()->json([
                'success' => true,
                'message' => 'Petugas berhasil ditambahkan!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Petugas gagal ditambahkan!'
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $petugas = User::where('hak_akses', 'petugas')->where('id_user', $user->id_user)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil load data Petugas',
            'data' => $petugas
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (!$user) {
            return response()->json([
                'success' => true,
                'message' => 'Data petugas tidak ditemukan!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'editNamaPetugas' => 'required|max:35',
            'editUsernamePetugas' => 'required|unique:users,username,'.$user->id_user.',id_user|max:15',
            'editPasswordPetugas' => 'nullable|min:6|max:12',
            'editNoTelpPetugas' => 'required|min:10|max:15',
            'editAlamatPetugas' => 'required|max:30',
            'editFotoPetugas' => 'nullable|image|max:10000',
            'editTglLahirPetugas' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data petugas gagal diupdate!',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('editFotoPetugas')){
            $path = public_path()."\images\Profile Petugas\\";

            if ($user->foto_profile != null) {
                $old_file = $path.$user->foto_profile;
                unlink($old_file);
            }

            $file = $request->file('editFotoPetugas');

            $image = Image::make($file);
            $image->resize(250, 250);
            
            $extension = $file->getClientOriginalExtension();
            $filename = md5(time()). '.' .$extension;
            $image->save($path . $filename, 80);

            $user->foto_profile = $filename;
        }

        $user->nama_user = $request->editNamaPetugas;
        $user->username = $request->editUsernamePetugas;
        if ($request->input('editPasswordPetugas') !== null) {
            $user->password = hash::make($request->editPasswordPetugas);
        };
        $user->notelp = $request->editNoTelpPetugas;
        $user->tgl_lahir = $request->editTglLahirPetugas;
        $user->alamat = $request->editAlamatPetugas;
        $user->update();

        return response()->json([
            'success' => true,
            'message' => 'Data petugas berhasil diupdate!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Data petugas tidak ditemukan!'
            ], 404);
        }

        $foto_petugas = 'images/Profile Petugas/'.$user->foto_profile;
		if(file_exists($foto_petugas))
		{
		  unlink($foto_petugas);
		}

        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data User berhasil dihapus!'
        ], 200);
    }
}
