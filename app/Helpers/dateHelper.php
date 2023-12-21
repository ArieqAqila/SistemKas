<?php

namespace app\Helpers;

class DateHelper
{
    /**
     * Format tanggal ke format Indonesia.
     *
     * @param string $date Tanggal dalam format Y-m-d H:i:s.
     * @return string Tanggal dalam format Indonesia.
     */
    public static function formatDateIndonesia($date): string
    {
        $Hari = array ("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu",);
        $Bulan = array ("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        
        $tahun 	 = substr($date, 0, 4);
        $bulan 	 = substr($date, 5, 2);
        $tgl	 = substr($date, 8, 2);
        $waktu	 = substr($date,11, 5);
        $hari	 = date("w", strtotime($date));
            
        $result = $tgl." ".$Bulan[(int)$bulan-1]." ".$tahun;
        
        return $result;
    }

    /**
     * Format tanggal ke format Indonesia.
     *
     * @param string $date Tanggal dalam format Y-m-d H:i:s.
     * @return string Tanggal dalam format Indonesia.
     */
    public static function formatMonthIndonesia($date): string
    {
        $Bulan = array ("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        
        $tahun 	 = substr($date, 0, 4);
        $bulan 	 = substr($date, 5, 2);
            
        $result = $Bulan[(int)$bulan-1]." ".$tahun;
        
        return $result;
    }
}
