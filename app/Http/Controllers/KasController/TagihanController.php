<?php

namespace App\Http\Controllers\KasController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

use App\Models\Tagihan;
use App\Models\User;
use App\Models\KasMasuk;
use App\Models\Kategori;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wargaBelumLunas = User::leftJoin('tagihan', 'users.id_user', '=', 'tagihan.id_user')
        ->whereNull('tagihan.id_tagihan')
        ->where('users.hak_akses', '=', 'warga')
        ->select('users.*', 'users.id_user as id', DB::raw('"Belum lunas" as status'), 'tagihan.*'); 
    
        $wargaSudahLunas = User::leftJoin('tagihan', 'users.id_user', '=', 'tagihan.id_user')
        ->whereNotNull('tagihan.id_tagihan')
        ->where('users.hak_akses', '=', 'warga')
        ->select('users.*', 'users.id_user as id', DB::raw('null as status'), 'tagihan.*'); 

        //dd($wargaBelumLunas->get());

        $warga = $wargaBelumLunas->union($wargaSudahLunas)->get();

        $currentMonth = Carbon::now()->month;

        Tagihan::whereMonth('tgl_tagihan', '<=', $currentMonth)
                 ->update(['status_tagihan' => 'Belum Lunas']);

        Tagihan::whereMonth('tgl_tagihan', '>', $currentMonth)
                 ->update(['status_tagihan' => 'Lunas']);

        return view('admin/pages/data-tagihan', compact('warga'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required',
            'inNominalTagihan' => 'required|numeric',
            'inNominalDibayar' => 'required|numeric|gt:inNominalTagihan',
            'inTglTagihan' => 'required',
        ], [
            'inNominalDibayar.gt' => 'Nominal yang dibayar kurang',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Konfirmasi',
                'errors' => $validator->errors(),
            ], 422);
        }
        dd('gagal');

        $cek_tagihan = Tagihan::where('id_user', $request->id_user)->first();
        
        if ($cek_tagihan) {
            $tglTagihan = Carbon::parse($cek_tagihan->tgl_tagihan)->addMonths($request->inTglTagihan)->toDateString();
        } else {
            $tglTagihan = Carbon::now()->addMonths($request->inTglTagihan)->toDateString();
        }

        $tagihan = Tagihan::updateOrCreate(
            ['id_user' => $request->id_user],
            [
                'nominal_sumbangan' => $request->inNominalDibayar - $request->inNominalTagihan,
                'status_tagihan' => 'LUNAS',
                'tgl_tagihan' => $tglTagihan    
            ]
        );

        $kas_masuk = KasMasuk::create([
            'nominal_masuk' => $request->inNominalDibayar,
            'tgl_masuk' => Carbon::now()->format('Y-m-d'),
            'deskripsi_masuk' => 'Tagihan Kas ' . $tagihan->user->username
        ]);

        if ($tagihan && $kas_masuk) {
            return response()->json([
                'success' => true,
                'message' => 'Tagihan kas berhasil dikonfirmasi!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan kas gagal dikonfirmasi!'
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tagihan $tagihan)
    {
        $tagihan_warga = Tagihan::where('id_tagihan', $tagihan->id_tagihan)->with('user')->first();
        
        return response()->json([
            'success' => true,
            'message' => 'Berhasil load data tagihan Warga',
            'data' => $tagihan_warga,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        if (!$tagihan) {
            return response()->json([
                'success' => true,
                'message' => 'Data tagihan warga tidak ditemukan!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_tagihan' => 'required',
            'editNominalTagihan' => 'required',
            'editNominalDibayar' => 'required',
            'editTglTagihan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tagihan warga tidak valid!',
                'errors' => $validator->errors()
            ], 422);
        }

        $tagihan->nominal_sumbangan = $request->editNominalDibayar - $request->editNominalTagihan;
        $tagihan->tgl_tagihan = $request->editTglTagihan;
        $tagihan->update();
        
        if (!$tagihan->update()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tagihan gagal diupdate!'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data warga berhasil diupdate!'
        ], 200);
    }

    private function generateCsvData($dataTagihan)
    {
        $headers = [
            'No.',
            'Nama Warga',
            'Nominal Tagihan Kas',
            'Nominal Sumbangan Kas',
            'Tanggal Tagihan Kas',
        ];

        $rows = [];
        $no = 1;

        // Add transaction data to each row
        foreach ($dataTagihan as $item) {
            $rowData = [
                $no++,
                $item->user->nama_user,
                $item->nominal_tagihan,
                $item->nominal_sumbangan,
                $item->tgl_tagihan,
            ];

            $rows[] = implode(',', $rowData);
        }

        // Combine headers and rows
        $data = implode(',', $headers) . "\n" . implode("\n", $rows);

        return $data;
    }

    public function downloadLaporan()
    {
        $dataTagihan = Tagihan::all();

        $csvData = $this->generateCsvData($dataTagihan);

        $response = Response::make($csvData);
        $response->header('Content-Type', 'text/csv');
        $response->header('Content-Disposition', 'attachment; filename="Data Tagihan.csv"');
    
        return $response;
    }
}
