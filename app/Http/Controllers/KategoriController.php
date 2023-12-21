<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all();
        
        return view('admin/pages/data-kategori', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inNamaKategori' => 'required|max:30',
            'inNominalKategori' => 'required|numeric|max:999999999',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data kategori tidak valid!',
                'errors' => $validator->errors(),
            ], 422);
        }

        $kategori = new Kategori();

        $kategori->nama_kategori = $request->inNamaKategori;
        $kategori->nominal_kategori = $request->inNominalKategori;
        $kategori->save();

        if ($kategori) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kategori gagal ditambahkan!'
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        return response()->json([
            'success' => true,
            'message' => 'Berhasil load data kategori',
            'data' => $kategori
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required',
            'editNamaKategori' => 'required|max:30',
            'editNominalKategori' => 'required|numeric|max:999999999',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data kategori tidak valid!',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Data warga tidak ditemukan!'
            ], 404);
        }

        $kategori->nama_kategori = $request->editNamaKategori;
        $kategori->nominal_kategori = $request->editNominalKategori;
        $kategori->update();

        if ($kategori) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diupdate!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kategori gagal diupdate!'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan!'
            ], 404);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus!'
        ], 200);
    }
}
