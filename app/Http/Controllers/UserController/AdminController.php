<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = User::where('hak_akses', 'admin')->get();
        return view('admin/pages/data-admin', compact('admin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inNamaAdmin' => 'Required|max:35',
            'inUsernameAdmin' => 'required|unique:users,username|max:15',
            'inPasswordAdmin' => 'required|max:12',
            'inNoTelpAdmin' => 'required|max:15',
            'inAlamatAdmin' => 'required',
            'inFotoAdmin' => 'required|image|max:10000',
            'inTglLahirAdmin' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Admin gagal ditambahkan!',
                'errors' => $validator->errors(),
            ], 422);
        }

        $admin = new User;

        if ($request->hasFile('inFotoAdmin')) {
            $path = public_path()."\images\Profile Admin\\";
            $file = $request->file('inFotoAdmin');
            
            $image = Image::make($file);
            $image->resize(250, 250);

            $extension = $file->getClientOriginalExtension();
            $filename = md5(time()). '.' .$extension;
            $image->save($path . $filename);

            $admin->foto_profile = $filename;
        }

        $admin->nama_user = $request->inNamaAdmin;
        $admin->username = $request->inUsernameAdmin;
        $admin->password = hash::make($request->inPasswordAdmin);
        $admin->notelp = $request->inNoTelpAdmin;
        $admin->tgl_lahir = $request->inTglLahirAdmin;
        $admin->alamat = $request->inAlamatAdmin;
        $admin->hak_akses = 'admin';
        $admin->is_first_login = true;
        $admin->save();

        if ($admin) {
            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil ditambahkan!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Admin gagal ditambahkan!'
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $admin = User::where('hak_akses', 'admin')->where('id_user', $user->id_user)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil load data Admin',
            'data' => $admin
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
                'message' => 'Data admin tidak ditemukan!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'editNamaAdmin' => 'required|max:35',
            'editUsernameAdmin' => 'required|unique:users,username,'.$user->id_user.',id_user|max:15',
            'editPasswordAdmin' => 'max:12',
            'editNoTelpAdmin' => 'required|max:15',
            'editAlamatAdmin' => 'required|max:30',
            'editFotoAdmin' => 'image|max:10000',
            'editTglLahirAdmin' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data admin gagal diupdate!',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('editFotoAdmin')){
            $path = public_path()."\images\Profile Admin\\";

            if ($user->foto_profile != null) {
                $old_file = $path.$user->foto_profile;
                unlink($old_file);
            }

            $file = $request->file('editFotoAdmin');

            $image = Image::make($file);
            $image->resize(250, 250);

            $extension = $file->getClientOriginalExtension();
            $filename = md5(time()). '.' .$extension;
            $image->save($path . $filename);

            $user->foto_profile = $filename;
        }

        $user->nama_user = $request->editNamaAdmin;
        $user->username = $request->editUsernameAdmin;
        if ($request->input('editPasswordAdmin') !== null) {
            $user->password = hash::make($request->editPasswordAdmin);
        };
        $user->notelp = $request->editNoTelpAdmin;
        $user->tgl_lahir = $request->editTglLahirAdmin;
        $user->alamat = $request->editAlamatAdmin;
        $user->update();

        return response()->json([
            'success' => true,
            'message' => 'Data admin berhasil diupdate!'
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
                'message' => 'Data admin tidak ditemukan!'
            ], 404);
        }

        $foto_admin = 'images/Profile Admin/'.$user->foto_profile;
		if(file_exists($foto_admin))
		{
		  unlink($foto_admin);
		}

        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data User berhasil dihapus!'
        ], 200);
    }
}
