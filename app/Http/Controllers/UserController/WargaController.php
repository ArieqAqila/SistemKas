<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;


class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warga = User::with('kategori')->where('hak_akses', 'warga')->get();
        $kategori = Kategori::all();
        return view('admin/pages/data-warga', compact('warga', 'kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inNamaWarga' => 'required|max:35',
            'inUsernameWarga' => 'required|unique:users,username|min:6|max:15',
            'inPasswordWarga' => 'required|min:6|max:12',
            'inNoTelpWarga' => 'nullable|min:10|max:15',
            'inAlamatWarga' => 'required',
            'inFotoWarga' => 'nullable|image|max:10000',
            'inTglLahirWarga' => 'nullable|date',
            'inKategori' => 'required',
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
            $image->save($path . $filename, 80);

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
        
        $warga->id_kategori = $request->inKategori;
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
            'editUsernameWarga' => 'required|unique:users,username,'.$user->id_user.',id_user|min:6|max:15',
            'editPasswordWarga' => 'nullable|min:6|max:12',
            'editNoTelpWarga' => 'nullable|min:10|max:15',
            'editAlamatWarga' => 'required|max:30',
            'editFotoWarga' => 'nullable|image|max:10000',
            'editTglLahirWarga' => 'nullable|date',
            'editKategori' => 'required',
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
            $image->save($path . $filename, 80);

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
        $user->id_kategori = $request->editKategori;
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

        if ($user->foto_profile) {
            $foto_warga = 'images/Profile Warga/'.$user->foto_profile;
            if(file_exists($foto_warga))
            {
                unlink($foto_warga);
            }
        }

        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data User berhasil dihapus!'
        ], 200);
    }

    /**
     * User password reset.
     */
    public function ResetPassword(Request $request) {
        $user = User::where('hak_akses', 'warga')->where('id_user', Auth::user()->id_user);

        if (!$user) {
            return abort(404);
        }

        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required|min:6|max:12|different:oldPassword',
            'confirmPassword' => 'required|same:newPassword',
        ],[
            'oldPassword.required' => 'Password saat ini wajib diisi!',
            'newPassword.required' => 'Password baru wajib diisi!',
            'newPassword.min' => 'Password baru kurang dari 6 karakter!',
            'newPassword.max' => 'Password baru lebih dari 12 karakter!',
            'newPassword.different' => 'Password baru sama dengan password saat ini!',
            'confirmPassword.required' => 'Konfirmasi password wajib diisi!',
            'confirmPassword.same' => 'Konfirmasi password berbeda dengan password baru!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Password yang diinput tidak valid!',
                'errors' => $validator->errors()
            ], 422);
        }

        $authUser = Auth::user();
        $user = User::where('id_user', $authUser->id_user)->first();
        $oldPassword = $user->password;        

        if (!Hash::check($request->oldPassword, $oldPassword)) {
            return response()->json([
                'success' => false,
                'message' => 'Password yang diinput tidak valid!',
                'errors' => ['oldPassword' => ['Password saat ini tidak sama!']],
            ], 422);
        }

        $user->password = Hash::make($request->newPassword);
        $user->is_first_login = false;
        $user->save();

        return redirect()->route('dashboard-warga');
    }

    /**
     * User profile update/edit.
     */
    public function profileEdit(Request $request) {
        $validator = Validator::make($request->all(), [
            'editUsername' => 'sometimes|min:6|max:15|unique:users,username',
            'editNama' => 'sometimes|max:50',
            'editTglLahir' => 'sometimes|date',
            'editNoTelp' => 'sometimes|min:10|max:15',         
            'editProfilePic' => 'sometimes|image|mimes:jpeg,jpg,png|max:10000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('username', Auth::user()->username)->first();
        $user->username = $request->editUsername ?? $user->username;
        $user->nama_user = $request->editNama ?? $user->nama_user;
        $user->tgl_lahir = $request->editTglLahir ?? $user->tgl_lahir;
        $user->notelp = $request->editNoTelp ?? $user->notelp;

        if ($request->hasFile('editProfilePic')){
            $path = public_path()."\images\Profile Warga\\";

            if ($user->foto_profile != null) {
                $old_file = $path.$user->foto_profile;
                unlink($old_file);
            }

            $file = $request->file('profilePic');
            $extension = $file->getClientOriginalExtension();
            $filename = md5(time()). '.' .$extension;
            $file->move($path, $filename);
            $user->foto_profile = $filename;
        }

        $user->update();

        return back()->with('success', 'Biodata diperbarui!');
    }
}
