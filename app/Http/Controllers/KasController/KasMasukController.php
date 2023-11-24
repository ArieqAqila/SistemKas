<?php

namespace App\Http\Controllers\KasController;

use App\Http\Controllers\Controller;
use App\Models\KasMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;

class KasMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kas_masuk = KasMasuk::all();
        $total = KasMasuk::sum('nominal_masuk');
        
        return view('admin/pages/data-kas-masuk', compact('kas_masuk','total'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inNominalMasuk' => 'required',
            'inTanggalMasuk' => 'required',
            'inDeskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data kas masuk tidak valid!',
                'errors' => $validator->errors(),
            ], 422);
        }

        $kas_masuk = new KasMasuk();

        $kas_masuk->nominal_masuk = $request->inNominalMasuk;
        $kas_masuk->tgl_masuk = $request->inTanggalMasuk;
        $kas_masuk->deskripsi_masuk = $request->inDeskripsi;
        $kas_masuk->save();

        if ($kas_masuk) {
            return response()->json([
                'success' => true,
                'message' => 'Kas masuk berhasil ditambahkan!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kas masuk gagal ditambahkan!'
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KasMasuk $kasMasuk)
    {
        return response()->json([
            'success' => true,
            'message' => 'Berhasil load data kas masuk',
            'data' => $kasMasuk
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KasMasuk $kasMasuk)
    {
        $validator = Validator::make($request->all(), [
            'id_masuk' => 'required',
            'editNominalMasuk' => 'required',
            'editTanggalMasuk' => 'required',
            'editDeskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data kas masuk tidak valid!',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!$kasMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'Data warga tidak ditemukan!'
            ], 404);
        }

        $kasMasuk->nominal_masuk = $request->editNominalMasuk;
        $kasMasuk->tgl_masuk = $request->editTanggalMasuk;
        $kasMasuk->deskripsi_masuk = $request->editDeskripsi;
        $kasMasuk->update();

        if ($kasMasuk) {
            return response()->json([
                'success' => true,
                'message' => 'Kas masuk berhasil diupdate!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kas masuk gagal diupdate!'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KasMasuk $kasMasuk)
    {
        if (!$kasMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'Data warga tidak ditemukan!'
            ], 404);
        }

        $kasMasuk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data User berhasil dihapus!'
        ], 200);
    }

    private function generateCsvData($data_kas_masuk)
    {
        $headers = [
            'No.',
            'Nominal Kas Masuk',
            'Tanggal Kas Masuk',
            'Deskripsi',
        ];

        $rows = [];
        $no = 1;

        // Add transaction data to each row
        foreach ($data_kas_masuk as $item) {
            $rowData = [
                $no++,
                $item->nominal_masuk,
                $item->tgl_masuk,
                $item->deskripsi_masuk,
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
        $data_kas_masuk = KasMasuk::whereBetween('tgl_masuk', [$startDate, $endDate])->get();

        if ($data_kas_masuk->isEmpty()) {
            return back()->withErrors(['message' => 'No data available']);
        }

        $csvData = $this->generateCsvData($data_kas_masuk);

        $response = Response::make($csvData);
        $response->header('Content-Type', 'text/csv');
        $response->header('Content-Disposition', 'attachment; filename="Data Kas.csv"');
    
        return $response;
    }
}
