<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Helpers\Qs;

class RdcAcademicSystemSeeder extends Seeder
{
    /**
     * Seed data for RDC Academic System
     * Creates sample exams for both semesters
     *
     * @return void
     */
    public function run()
    {
        $currentSession = Qs::getSetting('current_session');

        // Créer les examens pour les 2 semestres
        $exams = [
            [
                'name' => 'Examen Semestriel 1',
                'semester' => 1,
                'year' => $currentSession,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Examen Semestriel 2',
                'semester' => 2,
                'year' => $currentSession,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($exams as $exam) {
            DB::table('exams')->updateOrInsert(
                ['semester' => $exam['semester'], 'year' => $exam['year']],
                $exam
            );
        }

        $this->command->info('✅ Examens semestriels créés avec succès!');
        $this->command->info('   - Semestre 1 (Périodes 1 & 2)');
        $this->command->info('   - Semestre 2 (Périodes 3 & 4)');
    }
}
