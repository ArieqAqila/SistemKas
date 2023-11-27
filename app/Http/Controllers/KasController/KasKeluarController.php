<?php

namespace App\Http\Controllers\KasController;

use App\Http\Controllers\Controller;
use App\Models\KasKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;

class KasKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kas_keluar = KasKeluar::all();
        $total = KasKeluar::sum('nominal_keluar');
        
        return view('admin/pages/data-kas-keluar', compact('kas_keluar','total'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inNominalKeluar' => 'required|numeric|max:9',
            'inTanggalKeluar' => 'required|date',
            'inDeskripsi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data kas keluar tidak valid!',
                'errors' => $validator->errors(),
            ], 422);
        }

        $kas_keluar = new KasKeluar();

        $kas_keluar->nominal_keluar = $request->inNominalKeluar;
        $kas_keluar->tgl_keluar = $request->inTanggalKeluar;
        $kas_keluar->deskripsi_keluar = $request->inDeskripsi;
        $kas_keluar->save();

        if ($kas_keluar) {
            return response()->json([
                'success' => true,
                'message' => 'Kas keluar berhasil ditambahkan!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kas keluar gagal ditambahkan!'
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KasKeluar $kasKeluar)
    {
        return response()->json([
            'success' => true,
            'message' => 'Berhasil load data kas keluar',
            'data' => $kasKeluar
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KasKeluar $kasKeluar)
    {
        $validator = Validator::make($request->all(), [
            'id_keluar' => 'required',
            'editNominalKeluar' => 'required|numeric|max:9',
            'editTanggalKeluar' => 'required|date',
            'editDeskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data kas keluar tidak valid!',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!$kasKeluar) {
            return response()->json([
                'success' => false,
                'message' => 'Data kas keluar tidak ditemukan!'
            ], 404);
        }

        $kasKeluar->nominal_keluar = $request->editNominalKeluar;
        $kasKeluar->tgl_keluar = $request->editTanggalKeluar;
        $kasKeluar->deskripsi_keluar = $request->editDeskripsi;
        $kasKeluar->update();

        if ($kasKeluar) {
            return response()->json([
                'success' => true,
                'message' => 'Kas keluar berhasil diupdate!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kas keluar gagal diupdate!'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KasKeluar $kasKeluar)
    {
        if (!$kasKeluar) {
            return response()->json([
                'success' => false,
                'message' => 'Data kas keluar tidak ditemukan!'
            ], 404);
        }

        $kasKeluar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data kas keluar berhasil dihapus!'
        ], 200);
    }

    private function generateCsvData($data_kas_keluar)
    {
        $headers = [
            'No.',
            'Nominal Kas Keluar',
            'Tanggal Kas Keluar',
            'Deskripsi',
        ];

        $rows = [];
        $no = 1;

        // Add transaction data to each row
        foreach ($data_kas_keluar as $item) {
            $rowData = [
                $no++,
                $item->nominal_keluar,
                $item->tgl_keluar,
                $item->deskripsi_keluar,
            ];

            $rows[] = implode(',', $rowData);
        }

        // Combine headers and rows
        $data = implode(',', $headers) . "\n" . implode("\n", $rows);

        return $data;
    }

    public function downloadLaporan(Request $request) {
        $validator = Validator::make($request->all(), [
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        if ($validator->fails()) {
            return back()->withErrors(['message' => 'Date Invalid']);
        }

        $startDate = Carbon::parse($request->startDate)->startOfDay();
        $endDate = Carbon::parse($request->endDate)->endOfDay();
        $data_kas_keluar = KasKeluar::whereBetween('tgl_keluar', [$startDate, $endDate])->get();

        if ($data_kas_keluar->isEmpty()) {
            return back()->withErrors(['message' => 'No data available']);
        }

        $csvData = $this->generateCsvData($data_kas_keluar);

        $response = Response::make($csvData);
        $response->header('Content-Type', 'text/csv');
        $response->header('Content-Disposition', 'attachment; filename="Data Kas.csv"');
    
        return $response;
    }
}