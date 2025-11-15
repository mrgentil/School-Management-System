<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\PeriodCalculator;
use App\Models\StudentRecord;
use App\Helpers\Qs;

class CalculatePeriodAverages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'periods:calculate {--student_id= : ID spécifique d\'un étudiant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculer/recalculer toutes les moyennes de périodes pour tous les étudiants ou un étudiant spécifique';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔄 Début du calcul des moyennes de périodes...');
        
        $year = Qs::getCurrentSession();
        $studentId = $this->option('student_id');

        if ($studentId) {
            // Calculer pour un seul étudiant
            $studentRecord = StudentRecord::where('user_id', $studentId)
                ->where('session', $year)
                ->first();

            if (!$studentRecord) {
                $this->error("❌ Étudiant #{$studentId} non trouvé pour la session {$year}");
                return 1;
            }

            $this->calculateForStudent($studentRecord, $year);
            $this->info("✅ Moyennes calculées pour l'étudiant #{$studentId}");
        } else {
            // Calculer pour tous les étudiants
            $studentRecords = StudentRecord::where('session', $year)->get();
            $total = $studentRecords->count();
            $bar = $this->output->createProgressBar($total);
            $bar->start();

            foreach ($studentRecords as $studentRecord) {
                $this->calculateForStudent($studentRecord, $year);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("✅ Moyennes calculées pour {$total} étudiants!");
        }

        return 0;
    }

    /**
     * Calculer les moyennes pour un étudiant
     * 
     * @param StudentRecord $studentRecord
     * @param string $year
     * @return void
     */
    protected function calculateForStudent($studentRecord, $year)
    {
        PeriodCalculator::updateAllPeriodAveragesForStudent(
            $studentRecord->user_id,
            $studentRecord->my_class_id,
            $studentRecord->section_id,
            $year
        );
    }
}
