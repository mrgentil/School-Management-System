<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddAcademicSystemSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ajouter les paramètres du système académique
        DB::table('settings')->insert([
            [
                'type' => 'academic_system',
                'description' => 'rdc',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'period_count',
                'description' => '4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'semester_count',
                'description' => '2',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')->whereIn('type', [
            'academic_system',
            'period_count',
            'semester_count'
        ])->delete();
    }
}
