<?php

if (!function_exists('terbilang')) {
    function terbilang(int|float $angka): string
    {
        $angka = (int) abs($angka);
        $satuan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima',
                   'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh',
                   'sebelas', 'dua belas', 'tiga belas', 'empat belas',
                   'lima belas', 'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'];

        if ($angka < 20) return $satuan[$angka];
        if ($angka < 100) {
            $puluhan = intdiv($angka, 10);
            $sisa = $angka % 10;
            return $satuan[$puluhan] . ' puluh' . ($sisa ? ' ' . $satuan[$sisa] : '');
        }
        if ($angka < 200) {
            $sisa = $angka - 100;
            return 'seratus' . ($sisa ? ' ' . terbilang($sisa) : '');
        }
        if ($angka < 1000) {
            $ratusan = intdiv($angka, 100);
            $sisa = $angka % 100;
            return $satuan[$ratusan] . ' ratus' . ($sisa ? ' ' . terbilang($sisa) : '');
        }
        if ($angka < 2000) {
            $sisa = $angka - 1000;
            return 'seribu' . ($sisa ? ' ' . terbilang($sisa) : '');
        }
        if ($angka < 1000000) {
            $ribuan = intdiv($angka, 1000);
            $sisa = $angka % 1000;
            return terbilang($ribuan) . ' ribu' . ($sisa ? ' ' . terbilang($sisa) : '');
        }
        if ($angka < 1000000000) {
            $jutaan = intdiv($angka, 1000000);
            $sisa = $angka % 1000000;
            return terbilang($jutaan) . ' juta' . ($sisa ? ' ' . terbilang($sisa) : '');
        }
        $miliar = intdiv($angka, 1000000000);
        $sisa = $angka % 1000000000;
        return terbilang($miliar) . ' miliar' . ($sisa ? ' ' . terbilang($sisa) : '');
    }
}
