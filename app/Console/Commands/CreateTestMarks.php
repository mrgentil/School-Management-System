<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\TestMarksSeeder;

class CreateTestMarks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marks:create-test-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer des données de test pour la feuille de tabulation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎯 Création de données de test pour la feuille de tabulation...');
        
        if ($this->confirm('Cela va supprimer toutes les notes existantes. Continuer ?')) {
            $seeder = new TestMarksSeeder();
            $seeder->run();
            
            $this->info('✅ Données de test créées avec succès !');
            $this->info('🔗 Testez maintenant: http://localhost:8000/marks/tabulation');
        } else {
            $this->info('❌ Opération annulée.');
        }
    }
}
