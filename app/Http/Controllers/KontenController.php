<?php

namespace App\Http\Controllers;

use App\Models\Konten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;

class KontenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data_konten = Konten::with('user')->get();

        return view('admin/pages/data-konten', compact('data_konten'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inJudulKegiatan' => 'required|max:50',
            'inGambarKegiatan' => 'required|image|max:10000',
            'inIsiKonten' => 'required',
            'inTglRilisKonten' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data konten kegiatan invalid!',
                'errors' => $validator->errors(),
            ], 422);
        }

        $konten = new Konten;

        $konten->id_user = Auth::user()->id_user;
        $konten->judul_konten = $request->inJudulKegiatan;
        $konten->isi_konten = $request->inIsiKonten;
        $konten->tgl_konten = $request->inTglRilisKonten;

        if ($request->hasFile('inGambarKegiatan')) {
            $path = public_path()."\images\Konten Kegiatan\\";
            $file = $request->file('inGambarKegiatan');

            $image = Image::make($file);
            $image = $image->resize(1050, null);
            $image->crop(1050, 300);

            $extension = $file->getClientOriginalExtension();
            $filename = md5(time()). '.' .$extension;
            $image->save($path . $filename, 80,);
            $konten->gambar = $filename;
        }

        $konten->save();

        if ($konten) {
            return response()->json([
                'success' => true,
                'message' => 'Konten kegiatan berhasil ditambahkan!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Konten kegiatan gagal ditambahkan!'
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Konten $konten)
    {
        $konten = Konten::with('user')->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil load data Konten Kegiatan',
            'data' => $konten
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Konten $konten)
    {
        if (!$konten) {
            return response()->json([
                'success' => true,
                'message' => 'Data konten kegiatan tidak ditemukan!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'editJudulKegiatan' => 'required|max:50',
            'editGambarKegiatan' => 'image|max:10000',
            'editIsiKonten' => 'required',
            'editTglRilisKonten' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data konten kegiatan gagal diupdate!',
                'errors' => $validator->errors()
            ], 422);
        }

        $konten->judul_konten = $request->editJudulKegiatan;
        $konten->isi_konten = $request->editIsiKonten;
        $konten->tgl_konten = $request->editTglRilisKonten;

        if ($request->hasFile('editGambarKegiatan')){
            $path = public_path()."\images\Konten Kegiatan\\";

            if ($konten->foto_profile != null) {
                $old_file = $path.$konten->gambar;
                unlink($old_file);
            }

            $file = $request->file('editGambarKegiatan');

            $image = Image::make($file);
            $image = $image->resize(1050, null);
            $image->crop(1050, 300);

            $extension = $file->getClientOriginalExtension();
            $filename = md5(time()). '.' .$extension;
            $image->save($path . $filename, 80,);
            $konten->gambar = $filename;
        }

        $konten->update();

        return response()->json([
            'success' => true,
            'message' => 'Data konten kegiatan berhasil diupdate!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Konten $konten)
    {
        if (!$konten) {
            return response()->json([
                'success' => false,
                'message' => 'Data warga tidak ditemukan!'
            ], 404);
        }

        $gambar_konten = 'images/Konten Kegiatan/'.$konten->gambar;
		if(file_exists($gambar_konten))
		{
		  unlink($gambar_konten);
		}

        $konten->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data konten kegiatan berhasil dihapus!'
        ], 200);
    }
}
