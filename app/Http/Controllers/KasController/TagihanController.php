<?php

namespace App\Http\Controllers\KasController;

use App\Http\Controllers\Controller;
use App\Helpers\DateHelper;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\Tagihan;
use App\Models\User;
use App\Models\KasMasuk;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warga = User::with('kategori')
                    ->leftJoin('tagihan', 'users.id_user', '=', 'tagihan.id_user')
                    ->where('users.hak_akses', '=', 'warga')
                    ->select(
                        'users.*',
                        'users.id_user as id',                        
                        'tagihan.*'
                    )
                    ->get();


        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        Tagihan::where(function ($query) use ($currentYear, $currentMonth) {
            $query->whereYear('tgl_tagihan', '<=', $currentYear)
                  ->whereMonth('tgl_tagihan', '<=', $currentMonth);
        })
        ->orWhere(function ($query) use ($currentYear, $currentMonth) {
            $query->whereYear('tgl_tagihan', '>', $currentYear)
                  ->orWhereMonth('tgl_tagihan', '>', $currentMonth);
        })
        ->update(['status_tagihan' => DB::raw('CASE WHEN tgl_tagihan <= NOW() THEN "Belum Lunas" ELSE "Lunas" END')]);
        

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
            'inNominalDibayar' => 'required|numeric',
            'inTglTagihan' => 'required|numeric|min:1',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        if ($request->inNominalDibayar < $request->inNominalTagihan) {
            return response()->json([
                'success' => false,
                'message' => 'Nominal dibayar tidak cukup',
                'errors' => ['inNominalDibayar' => ['Nominal yang dibayar kurang!']],
            ], 422);
        }

        try {
            DB::beginTransaction();
            $cek_tagihan = Tagihan::where('id_user', $request->id_user)->first();
        
            $tagihan = Tagihan::updateOrCreate(
                ['id_user' => $request->id_user],
                [
                    'nominal_tertagih' => $request->inNominalDibayar,
                    'nominal_sumbangan' => $request->inNominalDibayar - $request->inNominalTagihan,
                    'status_tagihan' => 'Lunas',
                    'tgl_tagihan' => Carbon::parse($cek_tagihan->tgl_tagihan ?? Carbon::now())->addMonths($request->inTglTagihan)->toDateString(),
                ]
            );
        
            KasMasuk::create([
                'nominal_masuk' => $request->inNominalDibayar,
                'tgl_masuk' => Carbon::now()->format('Y-m-d'),
                'deskripsi_masuk' => 'Tagihan Kas ' . $tagihan->user->username,
            ]);
        
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();
        
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat tagihan untuk warga.',
                'error' => $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tagihan kas untuk warga "' . $tagihan->user->username . '" berhasil dikonfirmasi!',
        ], 200);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tagihan $tagihan)
    {
        $tagihan_warga = Tagihan::where('id_tagihan', $tagihan->id_tagihan)
            ->with('user', 'user.kategori')
            ->first();

        if ($tagihan_warga) {
            $tanggalTagihan = Carbon::create($tagihan_warga->tgl_tagihan)->format('m-y');
            $tanggalSaatIni = Carbon::now()->format('m-y');
    
            // Calculate the difference in months directly
            $selisihBulan = Carbon::createFromFormat('m-y', $tanggalSaatIni)
                ->diffInMonths(Carbon::createFromFormat('m-y', $tanggalTagihan));
            
            $tagihanWarga = $tagihan_warga->user->kategori->nominal_kategori;
    
            $tagihanWarga = $tanggalTagihan >= $tanggalSaatIni 
                            ? $tagihanWarga 
                            : $selisihBulan * $tagihanWarga;
            
            $tagihan_warga->user->kategori->nominal_kategori = $tagihanWarga;
        }
        
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
                'success' => false,
                'message' => 'Data tagihan tidak ditemukan!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'editNominalTertagih' => 'required|numeric',
            'editTglTagihan' => 'required|date'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tagihan tidak valid!',
                'errors' => $validator->errors()
            ], 422);
        }
    
        DB::transaction(function () use ($tagihan, $request) {
            $tagihan->update([
                'nominal_tertagih' => $request->editNominalTertagih,
                'nominal_sumbangan' => $request->editNominalTertagih - $request->editNominalTagihan,
                'tgl_tagihan' => $request->editTglTagihan
            ]);
    
            $currentMonth = (int) date('n');
            $currentYear = (int) date('Y');
            $deskripsiMasuk = 'Tagihan Kas ' . $tagihan->user->username;
    
            $existingRecord = KasMasuk::where('deskripsi_masuk', $deskripsiMasuk)
                ->whereMonth('tgl_masuk', $currentMonth)
                ->whereYear('tgl_masuk', $currentYear)
                ->lockForUpdate()->first();
    
            if ($existingRecord) {
                $existingRecord->nominal_masuk = $request->editNominalTertagih;
                $existingRecord->save();
            } else {
                KasMasuk::create([
                    'deskripsi_masuk' => $deskripsiMasuk,
                    'tgl_masuk' => date('Y-m-d'),
                    'nominal_masuk' => $request->editNominalTertagih
                ]);
            }
        });
    
        return response()->json([
            'success' => true,
            'message' => 'Data tagihan berhasil diupdate!'
        ], 200);
    }

    private function generateXlsxData($dataTagihan)
    {

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Agency FB');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A2' => 'Tagihan Iuran Warga Bulan ……………………………',
            'A4' => 'No.',
            'B4' => 'Nama KK',
            'C4' => 'Alamat',
            'D4' => 'Iuran Kas',
            'D6' => 'Tagihan',
            'E6' => 'Tertagih',
            'F6' => 'Sumbangan',
            'G4' => 'Tenggat',
            'H4' => 'Keterangan',
        ];
        
        foreach ($headers as $cellRange => $label) {
            $sheet->setCellValue($cellRange, $label);
        
            $sheet->getStyle($cellRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cellRange)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }

        $rows = [];
        $no = 1;

        foreach ($dataTagihan as $item) {
            $rowData = [
                $no++,
                $item->user->nama_user,
                $item->user->alamat,
                $item->user->kategori->nominal_kategori,
                $item->nominal_tertagih,
                $item->nominal_sumbangan,
                DateHelper::formatMonthIndonesia($item->tgl_tagihan),
                'Tagihan kas ' . $item->status_tagihan,
            ];

            $rows[] = $rowData;
        }
        $sheet->fromArray($rows, null, 'A7'); 
        
        $lastDataRow = count($rows) + 6;

        $totalRow = $lastDataRow + 2;
        $sheet->setCellValue('C' . $totalRow, 'Total');
        $sheet->setCellValue('D' . $totalRow, "=SUM(D7:D{$lastDataRow})");
        $sheet->setCellValue('E' . $totalRow, "=SUM(E7:E{$lastDataRow})");
        $sheet->setCellValue('F' . $totalRow, "=SUM(F7:F{$lastDataRow})");

        $dateRow = $lastDataRow + 4;
        $sheet->setCellValue('B' . $dateRow, 'Bekasi,……………………………..');

        $signatureRow = $lastDataRow + 6;
        $sheet->setCellValue('B' . $signatureRow, 'Dibuat dan diserahkan oleh,');
        $sheet->setCellValue('B' . $signatureRow + 6, '(.....................)');
        $sheet->setCellValue('E' . $signatureRow, 'Diterima oleh,');
        $sheet->setCellValue('E' . $signatureRow + 6, '(.....................)');
        

        //Style and Format Section
        $sheet->getStyle('B7:H' . $totalRow)->getNumberFormat()->setFormatCode('_([$Rp] * #,##0_);_([$Rp] * (#,##0);_([$Rp] * "-"_);_(@_)');
        
        $sheet->getStyle('C' . $totalRow . ':F' . $totalRow)->getFont()->setBold(true); //bold the total section

        $sheet->getStyle('A2:H6')->getFont()->setBold(true); //Bold the headers

        $sheet->getStyle('A2')->getFont()->setSize(16);
        $sheet->getStyle('A4:H4')->getFont()->setSize(14); //set the headers font size to 16pt

        $sheet->getStyle('A7:A'. $lastDataRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B'. $signatureRow .':E'. $signatureRow + 6)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '0000000'], // Set color to match cell background color
                ],
            ],
        ];
        $sheet->getStyle('A2:H' . $totalRow)->applyFromArray($border);



        $columnWidths = [
            'A' => 42,
            'B' => 274,
            'C' => 162,
            'D' => 110,
            'E' => 110,
            'F' => 110,
            'G' => 164,
            'H' => 144
        ];

        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width, 'px');
        }
        

        $theCells = [
            'A2:H2',
            'A3:H3',
            'A4:A6',
            'B4:B6',
            'C4:C6',
            'D4:F5',
            'G4:G6',
            'H4:H6',
        ];
        $sheet->setMergeCells($theCells);

        foreach ($theCells as $cellRange) {
            $sheet->getStyle($cellRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cellRange)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }

        // Save the spreadsheet
        $xlsxFilePath = 'tagihanMain.xlsx';
        $writer = new Xlsx($spreadsheet);

        try {
            $writer->save($xlsxFilePath);
        } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {

            return null;
        }

        return $xlsxFilePath;
    }

    public function downloadLaporan()
    {
        $dataTagihan = Tagihan::with('user', 'user.kategori')->get();

        $xlsxData = $this->generateXlsxData($dataTagihan);

        if ($xlsxData === null) {
            // Handle the case where saving the spreadsheet failed
            return response()->json(['error' => 'Failed to generate XLSX file'], 500);
        }

        $response = response()->download($xlsxData, 'Laporan Tagihan '.DateHelper::formatMonthIndonesia(Carbon::now()).'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment',
        ]);

        return $response;
    }
}
