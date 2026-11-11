<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_periksa', function (Blueprint $table) {
            $table->foreignId('id_poli')
                ->nullable()
                ->after('id_dokter')
                ->constrained('poli')
                ->nullOnDelete();
        });

        DB::table('jadwal_periksa')
            ->whereNull('id_poli')
            ->orderBy('id')
            ->get(['id', 'id_dokter'])
            ->each(function (object $jadwal): void {
                $idPoli = DB::table('users')
                    ->where('id', $jadwal->id_dokter)
                    ->value('id_poli');

                if ($idPoli !== null) {
                    DB::table('jadwal_periksa')
                        ->where('id', $jadwal->id)
                        ->update(['id_poli' => $idPoli]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('jadwal_periksa', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_poli');
        });
    }
};
