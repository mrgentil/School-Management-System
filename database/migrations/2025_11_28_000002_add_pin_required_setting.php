<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter le paramètre pour activer/désactiver l'obligation du PIN
        if (!DB::table('settings')->where('type', 'pin_required_for_bulletin')->exists()) {
            DB::table('settings')->insert([
                'type' => 'pin_required_for_bulletin',
                'description' => 'no', // 'yes' ou 'no'
            ]);
        }
    }

    public function down(): void
    {
        DB::table('settings')->where('type', 'pin_required_for_bulletin')->delete();
    }
};
