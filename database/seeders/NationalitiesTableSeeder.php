<?php
namespace Database\Seeders;

use App\Models\Nationality;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class NationalitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Désactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Vider la table
        DB::table('nationalities')->truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $nationals = array(
            'Afghane', 'Albanaise', 'Algérienne', 'Américaine', 'Andorrane', 'Angolaise', 'Antiguaise', 'Argentine', 'Arménienne', 'Australienne', 'Autrichienne', 'Azerbaïdjanaise', 'Bahaméenne', 'Bahreïnienne', 'Bangladaise', 'Barbadienne', 'Batswanaise', 'Biélorusse', 'Belge', 'Bélizienne', 'Béninoise', 'Bhoutanaise', 'Bolivienne', 'Bosnienne', 'Brésilienne', 'Britannique', 'Brunéienne', 'Bulgare', 'Burkinabé', 'Birmane', 'Burundaise', 'Cambodgienne', 'Camerounaise', 'Canadienne', 'Cap-verdienne', 'Centrafricaine', 'Tchadienne', 'Chilienne', 'Chinoise', 'Colombienne', 'Comorienne', 'Congolaise (RDC)', 'Congolaise (République)', 'Costaricienne', 'Croate', 'Cubaine', 'Chypriote', 'Tchèque', 'Danoise', 'Djiboutienne', 'Dominicaine', 'Néerlandaise', 'Est-timoraise', 'Équatorienne', 'Égyptienne', 'Émirienne', 'Équato-guinéenne', 'Érythréenne', 'Estonienne', 'Éthiopienne', 'Fidjienne', 'Philippine', 'Finlandaise', 'Française', 'Gabonaise', 'Gambienne', 'Géorgienne', 'Allemande', 'Ghanéenne', 'Grecque', 'Grenadienne', 'Guatémaltèque', 'Guinéenne', 'Bissau-guinéenne', 'Guyanienne', 'Haïtienne', 'Herzégovinienne', 'Hondurienne', 'Hongroise', 'Islandaise', 'Indienne', 'Indonésienne', 'Iranienne', 'Irakienne', 'Irlandaise', 'Israélienne', 'Italienne', 'Ivoirienne', 'Jamaïcaine', 'Japonaise', 'Jordanienne', 'Kazakhe', 'Kényane', 'Koweïtienne', 'Kirghize', 'Laotienne', 'Lettone', 'Libanaise', 'Libérienne', 'Libyenne', 'Liechtensteinoise', 'Lituanienne', 'Luxembourgeoise', 'Macédonienne', 'Malgache', 'Malawienne', 'Malaisienne', 'Maldivienne', 'Malienne', 'Maltaise', 'Marshallaise', 'Mauritanienne', 'Mauricienne', 'Mexicaine', 'Micronésienne', 'Moldave', 'Monégasque', 'Mongole', 'Marocaine', 'Mozambicaine', 'Namibienne', 'Nauruane', 'Népalaise', 'Néo-zélandaise', 'Nicaraguayenne', 'Nigériane', 'Nigérienne', 'Nord-coréenne', 'Norvégienne', 'Omanaise', 'Pakistanaise', 'Palaosienne', 'Panaméenne', 'Papouane-néo-guinéenne', 'Paraguayenne', 'Péruvienne', 'Polonaise', 'Portugaise', 'Qatarienne', 'Roumaine', 'Russe', 'Rwandaise', 'Saint-lucienne', 'Salvadorienne', 'Samoane', 'Saint-marinaise', 'Santoméenne', 'Saoudienne', 'Écossaise', 'Sénégalaise', 'Serbe', 'Seychelloise', 'Sierra-léonaise', 'Singapourienne', 'Slovaque', 'Slovène', 'Salomonaise', 'Somalienne', 'Sud-africaine', 'Sud-coréenne', 'Espagnole', 'Sri-lankaise', 'Soudanaise', 'Surinamaise', 'Swazie', 'Suédoise', 'Suisse', 'Syrienne', 'Taïwanaise', 'Tadjike', 'Tanzanienne', 'Thaïlandaise', 'Togolaise', 'Tongienne', 'Trinidadienne', 'Tunisienne', 'Turque', 'Tuvaluane', 'Ougandaise', 'Ukrainienne', 'Uruguayenne', 'Ouzbèke', 'Vénézuélienne', 'Vietnamienne', 'Galloise', 'Yéménite', 'Zambienne', 'Zimbabwéenne'
        );

        foreach ($nationals as $n) {
            Nationality::create(['name' => $n]);
        }
    }
}
