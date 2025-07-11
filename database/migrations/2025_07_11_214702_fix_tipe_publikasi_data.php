<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Perbaiki data tipe_publikasi yang masih berupa string
        $publications = DB::table('publications')->get();
        
        foreach ($publications as $publication) {
            $tipePublikasi = $publication->tipe_publikasi;
            
            // Jika masih berupa string, ubah menjadi array JSON
            if (is_string($tipePublikasi) && !empty($tipePublikasi)) {
                $tipeArray = [$tipePublikasi];
                DB::table('publications')
                    ->where('id', $publication->id)
                    ->update(['tipe_publikasi' => json_encode($tipeArray)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback untuk data migration
    }
};
