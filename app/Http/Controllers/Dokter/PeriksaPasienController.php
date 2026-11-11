<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with([
            'pasien',
            'poli',
            'dokter',
            'jadwalPeriksa.dokter.poli',
            'periksas',
        ])
            ->where(function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId)
                    ->orWhere(function ($legacyQuery) use ($dokterId) {
                        $legacyQuery->whereNull('id_dokter')
                            ->whereHas('jadwalPeriksa', function ($scheduleQuery) use ($dokterId) {
                                $scheduleQuery->where('id_dokter', $dokterId);
                            });
                    });
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $this->daftarPasienMilikDokter($id);
        $obats = Obat::all();
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_daftar_poli' => ['required', 'exists:daftar_poli,id'],
            'obat_json' => 'required',
            'catatan' => 'nullable|string',
            'biaya_periksa' => 'required|integer',
        ]);

        $daftarPasien = $this->daftarPasienMilikDokter($validated['id_daftar_poli']);
        $obatIds = json_decode($request->obat_json, true);

        $periksa = Periksa::create([
            'id_daftar_poli' => $daftarPasien->id,
            'tgl_periksa' => now(),
            'catatan' => $request->catatan,
            'biaya_periksa' => $request->biaya_periksa + 150000,
        ]);

        if (is_array($obatIds)) {
            foreach ($obatIds as $idObat) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $idObat,
                ]);
            }
        }

        return redirect()
            ->route('periksa-pasien.index')
            ->with('success', 'Data periksa berhasil disimpan.');
    }

    private function daftarPasienMilikDokter(string|int $id): DaftarPoli
    {
        $dokterId = Auth::id();

        return DaftarPoli::whereKey($id)
            ->where(function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId)
                    ->orWhere(function ($legacyQuery) use ($dokterId) {
                        $legacyQuery->whereNull('id_dokter')
                            ->whereHas('jadwalPeriksa', function ($scheduleQuery) use ($dokterId) {
                                $scheduleQuery->where('id_dokter', $dokterId);
                            });
                    });
            })
            ->firstOrFail();
    }
}