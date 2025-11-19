<?php
namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{

    public function run()
    {
        // Désactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Vider la table
        DB::table('states')->truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Provinces de la République Démocratique du Congo
        $provinces = [
            'Kinshasa',
            'Kongo Central',
            'Kwilu',
            'Kwango',
            'Mai-Ndombe',
            'Kasaï',
            'Kasaï Oriental',
            'Kasaï Central',
            'Lomami',
            'Sankuru',
            'Maniema',
            'Sud-Kivu',
            'Nord-Kivu',
            'Ituri',
            'Haut-Uélé',
            'Bas-Uélé',
            'Tshopo',
            'Mongala',
            'Nord-Ubangi',
            'Sud-Ubangi',
            'Équateur',
            'Tshuapa',
            'Haut-Lomami',
            'Lualaba',
            'Haut-Katanga',
            'Tanganyika'
        ];

        foreach ($provinces as $province) {
            State::create(['name' => $province]);
        }
    }

}
