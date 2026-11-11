<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPasienController extends Controller
{
    public function index()
    {
        $riwayatPasien = Periksa::whereHas('daftarPoli', function ($query) {
            $this->filterMilikDokter($query);
        })->with([
            'daftarPoli.pasien',
            'daftarPoli.poli',
            'daftarPoli.dokter',
            'daftarPoli.jadwalPeriksa.dokter.poli',
            'detailPeriksas.obat'
        ])->orderBy('tgl_periksa', 'desc')->get();

        return view('dokter.riwayat-pasien.index', compact('riwayatPasien'));
    }

    public function show($id)
    {
        $periksa = Periksa::whereHas('daftarPoli', function ($query) {
            $this->filterMilikDokter($query);
        })->with([
            'daftarPoli.pasien',
            'daftarPoli.poli',
            'daftarPoli.dokter',
            'daftarPoli.jadwalPeriksa.dokter.poli',
            'detailPeriksas.obat'
        ])->findOrFail($id);

        return view('dokter.riwayat-pasien.show', compact('periksa'));
    }

    private function filterMilikDokter($query): void
    {
        $dokterId = Auth::id();

        $query->where('id_dokter', $dokterId)
            ->orWhere(function ($legacyQuery) use ($dokterId) {
                $legacyQuery->whereNull('id_dokter')
                    ->whereHas('jadwalPeriksa', function ($scheduleQuery) use ($dokterId) {
                        $scheduleQuery->where('id_dokter', $dokterId);
                    });
            });
    }
}