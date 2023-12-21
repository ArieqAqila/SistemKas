<?php

namespace App\Http\Controllers\KasController;

use App\Http\Controllers\Controller;
use App\Helpers\DateHelper;

use App\Models\KasKeluar;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

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
            'inNominalKeluar' => 'required|numeric|max:999999999',
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
            'editNominalKeluar' => 'required|numeric|max:999999999',
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

    private function generateXlsxData($dataKasMasuk, $startDate, $endDate)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Agency FB');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A2' => 'Kas Keluar '. DateHelper::formatDateIndonesia($startDate) . ' Sampai ' . DateHelper::formatDateIndonesia($endDate),
            'A4' => 'No.',
            'B4' => 'Nominal Kas Keluar',
            'C4' => 'Tanggal Kas Keluar',
            'D4' => 'Deskripsi',
        ];

        foreach ($headers as $cellRange => $label) {
            $sheet->setCellValue($cellRange, $label);
        
            $sheet->getStyle($cellRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cellRange)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            
            $sheet->getStyle($cellRange)->getFont()->setBold(true);
        }

        $rows = [];
        $no = 1;

        // Add transaction data to each row
        foreach ($dataKasMasuk as $item) {
            $rowData = [
                $no++,
                $item->nominal_keluar,
                DateHelper::formatDateIndonesia($item->tgl_keluar),
                $item->deskripsi_keluar,
            ];

            $rows[] = $rowData;
        }
        $sheet->fromArray($rows, null, 'A7');

        $lastDataRow = count($rows) + 6;

        //display total
        $totalRow = $lastDataRow + 2;
        $sheet->setCellValue('A' . $totalRow, 'Total');
        $sheet->setCellValue('B' . $totalRow, "=SUM(B7:B{$lastDataRow})");

        //display date
        $dateRow = $lastDataRow + 4;
        $sheet->setCellValue('B' . $dateRow, 'Bekasi,……………………………..');

        //display signature
        $signatureRow = $lastDataRow + 6;
        $sheet->setCellValue('C' . $signatureRow, 'Dibuat dan diserahkan oleh,');
        $sheet->setCellValue('C' . $signatureRow + 6, '(.....................)');
        $sheet->setCellValue('D' . $signatureRow, 'Diterima oleh,');
        $sheet->setCellValue('D' . $signatureRow + 6, '(.....................)');



        //Style and Format Section
        $sheet->getStyle('B7:B' . $totalRow)->getNumberFormat()->setFormatCode('_([$Rp] * #,##0_);_([$Rp] * (#,##0);_([$Rp] * "-"_);_(@_)');
        
        $sheet->getStyle('A' . $totalRow . ':B' . $totalRow)->getFont()->setBold(true); //bold the total section

        $sheet->getStyle('A2:D4')->getFont()->setBold(true); //Bold the headers

        $sheet->getStyle('A2')->getFont()->setSize(16);//set the title font size
        $sheet->getStyle('A4:D4')->getFont()->setSize(14); //set the headers font size to 16pt

        //center the "no." cell
        $sheet->getStyle('A7:A'. $lastDataRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        //center the signature cell
        $sheet->getStyle('C'. $signatureRow .':D'. $signatureRow + 6)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '0000000'], // Set color to match cell background color
                ],
            ],
        ];
        $sheet->getStyle('A2:D' . $totalRow)->applyFromArray($border);


        $columnWidths = [
            'A' => 45,
            'B' => 150,
            'C' => 175,
            'D' => 200
        ];

        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width, 'px');
        }
        

        $theCells = [
            'A2:D2',
            'A3:D3',
            'A4:A6',
            'B4:B6',
            'C4:C6',
            'D4:D6',
        ];
        $sheet->setMergeCells($theCells);

        foreach ($theCells as $cellRange) {
            $sheet->getStyle($cellRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cellRange)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }

        // Save the spreadsheet
        $xlsxFilePath = 'kasKeluarMain.xlsx';
        $writer = new Xlsx($spreadsheet);

        try {
            $writer->save($xlsxFilePath);
        } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {

            return null;
        }

        return $xlsxFilePath;
    }

    public function downloadLaporan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        if ($validator->fails()) {
            return back()->withErrors(['message' => 'Date Invalid']);
        }

        $startDate = Carbon::parse($request->startDate)->startOfDay();
        $endDate = Carbon::parse($request->endDate)->endOfDay();

        $dataKasMasuk = KasKeluar::whereBetween('tgl_keluar', [$startDate, $endDate])->get();

        if ($dataKasMasuk->isEmpty()) {
            return back()->withErrors(['message' => 'No data available']);
        }

        $xlsxData = $this->generateXlsxData($dataKasMasuk, $startDate, $endDate);

        if ($xlsxData === null) {
            // Handle the case where saving the spreadsheet failed
            return response()->json(['error' => 'Failed to generate XLSX file'], 500);
        }

        $response = response()->download($xlsxData, 'Laporan Kas Keluar '. DateHelper::formatDateIndonesia($startDate) . ' Sampai ' . DateHelper::formatDateIndonesia($endDate).'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment',
        ]);
    
        return $response;
    }

    public function truncateTable()
    {
        KasKeluar::truncate();

        return response()->json([
            'success' => true,
            'message' => 'Reset data kas keluar berhasil!'
        ], 200);
    }
}