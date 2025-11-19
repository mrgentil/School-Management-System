<?php
namespace Database\Seeders;

use App\Models\Lga;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LgasTableSeeder extends Seeder
{
    public function run()
    {
        // Désactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Vider la table
        DB::table('lgas')->truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ID de la province de Kinshasa (supposons que c'est 1)
        $kinshasa_province_id = 1;

        // Communes de Kinshasa
        $communes = [
            'Bandalungwa',
            'Barumbu', 
            'Bumbu',
            'Gombe',
            'Kalamu',
            'Kasa-Vubu',
            'Kimbanseke',
            'Kinshasa',
            'Kintambo',
            'Kisenso',
            'Lemba',
            'Limete',
            'Lingwala',
            'Makala',
            'Maluku',
            'Masina',
            'Matete',
            'Mont-Ngafula',
            'Ndjili',
            'Ngaba',
            'Ngaliema',
            'Ngiri-Ngiri',
            'Nsele',
            'Selembao'
        ];

        // Insérer les communes
        foreach($communes as $commune) {
            Lga::create([
                'state_id' => $kinshasa_province_id, 
                'name' => $commune
            ]);
        }
    }
}