<?php

namespace App\Http\Controllers;

use App\Models\KasMasuk;
use App\Models\KasKeluar;

use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{

    public function riwayatSaldoKas() {
        $total_kas_masuk = KasMasuk::sum('nominal_masuk');
        $total_kas_keluar = KasKeluar::sum('nominal_keluar');
        
        $riwayat_kas = KasMasuk::select('id_masuk as id', 'nominal_masuk as nominal', 'tgl_masuk as tanggal', 'deskripsi_masuk as deskripsi')
            ->where('deskripsi_masuk', 'Tagihan Kas '.Auth::user()->username)
            ->get();

        return view('warga/pages/riwayat-saldo', compact('riwayat_kas','total_kas_masuk','total_kas_keluar'));
    }
}
