<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PoliController extends Controller
{
    public function get()
    {
        $user = Auth::user();
        $user->ensureNoRm();
        $polis = Poli::orderBy('nama_poli')->get();
        $jadwal = JadwalPeriksa::with('dokter', 'poli')
            ->where('status', 'Aktif')
            ->whereNotNull('id_poli')
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('pasien.daftar', [
            'user' => $user,
            'polis' => $polis,
            'jadwals' => $jadwal,
        ]);
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'id_poli' => 'required|exists:poli,id',
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'nullable|string',
        ]);

        $jadwal = JadwalPeriksa::with('dokter')
            ->whereKey($validated['id_jadwal'])
            ->where('status', 'Aktif')
            ->where('id_poli', $validated['id_poli'])
            ->whereHas('dokter', function ($query) {
                $query->where('role', 'dokter');
            })
            ->first();

        if (!$jadwal) {
            return back()
                ->withErrors(['id_jadwal' => 'Pilih jadwal aktif yang sesuai dengan poli.'])
                ->withInput();
        }

        $jumlahSudahDaftar = DaftarPoli::where('id_jadwal', $validated['id_jadwal'])->count();

        DaftarPoli::create([
            'id_pasien' => Auth::id(),
            'id_jadwal' => $validated['id_jadwal'],
            'id_dokter' => $jadwal->id_dokter,
            'id_poli' => $validated['id_poli'],
            'keluhan' => $validated['keluhan'] ?? '',
            'no_antrian' => $jumlahSudahDaftar + 1,
        ]);

        return redirect()
            ->back()
            ->with('message', 'Berhasil Mendaftar ke Poli')
            ->with('type', 'success');
    }
}