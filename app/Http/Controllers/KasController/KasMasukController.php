<?php

namespace App\Http\Controllers\KasController;

use App\Http\Controllers\Controller;
use App\Helpers\DateHelper;

use App\Models\KasMasuk;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            'inNominalMasuk' => 'required|numeric|max:999999999',
            'inTanggalMasuk' => 'required|date',
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
            'editNominalMasuk' => 'required|numeric|max:999999999',
            'editTanggalMasuk' => 'required|date',
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
                'message' => 'Data kas masuk tidak ditemukan!'
            ], 404);
        }

        $kasMasuk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data kas masuk berhasil dihapus!'
        ], 200);
    }

    private function generateXlsxData($dataKasMasuk, $startDate, $endDate)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Agency FB');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A2' => 'Kas Masuk '. DateHelper::formatDateIndonesia($startDate) . ' Sampai ' . DateHelper::formatDateIndonesia($endDate),
            'A4' => 'No.',
            'B4' => 'Nominal Kas Masuk',
            'C4' => 'Tanggal Kas Masuk',
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
                $item->nominal_masuk,
                DateHelper::formatDateIndonesia($item->tgl_masuk),
                $item->deskripsi_masuk,
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
        $xlsxFilePath = 'kasMasukMain.xlsx';
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

        $dataKasMasuk = KasMasuk::whereBetween('tgl_masuk', [$startDate, $endDate])->get();

        if ($dataKasMasuk->isEmpty()) {
            return back()->withErrors(['message' => 'No data available']);
        }

        $xlsxData = $this->generateXlsxData($dataKasMasuk, $startDate, $endDate);

        if ($xlsxData === null) {
            // Handle the case where saving the spreadsheet failed
            return response()->json(['error' => 'Failed to generate XLSX file'], 500);
        }

        $response = response()->download($xlsxData, 'Laporan Kas Masuk '. DateHelper::formatDateIndonesia($startDate) . ' Sampai ' . DateHelper::formatDateIndonesia($endDate).'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment',
        ]);
    
        return $response;
    }

    public function truncateTable()
    {
        KasMasuk::truncate();

        return response()->json([
            'success' => true,
            'message' => 'Reset data kas masuk berhasil!'
        ], 200);
    }
}
