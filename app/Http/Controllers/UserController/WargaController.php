<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warga = User::where('hak_akses', 'warga')->get();
        return view('admin/pages/data-warga', compact('warga'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inNamaWarga' => 'Required|max:35',
            'inUsernameWarga' => 'required|unique:users,username|max:15',
            'inPasswordWarga' => 'required|max:12',
            'inNoTelpWarga' => 'required|max:15',
            'inAlamatWarga' => 'required',
            'inFotoWarga' => 'required|image|max:10000',
            'inTglLahirWarga' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Warga gagal ditambahkan!',
                'errors' => $validator->errors(),
            ], 422);
        }

        $warga = new User;

        if ($request->hasFile('inFotoWarga')) {
            $path = public_path()."\images\Profile Warga\\";
            $file = $request->file('inFotoWarga');

            $image = Image::make($file);
            $image->resize(250, 250);

            $extension = $file->getClientOriginalExtension();
            $filename = md5(time()). '.' .$extension;
            $file->save($path, $filename);

            $warga->foto_profile = $filename;
        }

        $warga->nama_user = $request->inNamaWarga;
        $warga->username = $request->inUsernameWarga;
        $warga->password = hash::make($request->inPasswordWarga);
        $warga->notelp = $request->inNoTelpWarga;
        $warga->tgl_lahir = $request->inTglLahirWarga;
        $warga->alamat = $request->inAlamatWarga;
        $warga->hak_akses = 'warga';
        $warga->is_first_login = true;
        $warga->save();

        if ($warga) {
            return response()->json([
                'success' => true,
                'message' => 'Warga berhasil ditambahkan!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Warga gagal ditambahkan!'
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $warga = User::where('hak_akses', 'warga')->where('id_user', $user->id_user)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil load data Warga',
            'data' => $warga
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
                'message' => 'Data warga tidak ditemukan!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'editNamaWarga' => 'required|max:35',
            'editUsernameWarga' => 'required|unique:users,username,'.$user->id_user.',id_user|max:15',
            'editPasswordWarga' => 'max:12',
            'editNoTelpWarga' => 'required|max:15',
            'editAlamatWarga' => 'required|max:30',
            'editFotoWarga' => 'image|max:10000',
            'editTglLahirWarga' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data warga gagal diupdate!',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('editFotoWarga')){
            $path = public_path()."\images\Profile Warga\\";

            if ($user->foto_profile != null) {
                $old_file = $path.$user->foto_profile;
                unlink($old_file);
            }

            $file = $request->file('editFotoWarga');

            $image = Image::make($file);
            $image->resize(250, 250);

            $extension = $file->getClientOriginalExtension();
            $filename = md5(time()). '.' .$extension;
            $file->save($path, $filename);

            $user->foto_profile = $filename;
        }

        $user->nama_user = $request->editNamaWarga;
        $user->username = $request->editUsernameWarga;
        if ($request->input('editPasswordWarga') !== null) {
            $user->password = hash::make($request->editPasswordWarga);
        };
        $user->notelp = $request->editNoTelpWarga;
        $user->tgl_lahir = $request->editTglLahirWarga;
        $user->alamat = $request->editAlamatWarga;
        $user->update();

        return response()->json([
            'success' => true,
            'message' => 'Data warga berhasil diupdate!'
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
                'message' => 'Data warga tidak ditemukan!'
            ], 404);
        }

        $foto_warga = 'images/Profile Warga/'.$user->foto_profile;
		if(file_exists($foto_warga))
		{
		  unlink($foto_warga);
		}

        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data User berhasil dihapus!'
        ], 200);
    }
}
