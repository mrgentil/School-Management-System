<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRdcAcademicSystem extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration pour implémenter le système académique RDC:
     * - 4 Périodes pour les interrogations/devoirs
     * - 2 Semestres pour les examens
     *
     * @return void
     */
    public function up()
    {
        // 1. Modifier la table exams: remplacer 'term' par 'semester'
        Schema::table('exams', function (Blueprint $table) {
            // Renommer term en semester (1 ou 2 au lieu de 1, 2, 3)
            $table->renameColumn('term', 'semester');
        });

        // 2. Ajouter 'period' à la table assignments
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'period')) {
                $table->tinyInteger('period')->default(1)->after('subject_id')
                    ->comment('Période: 1, 2, 3 ou 4');
            }
        });

        // 3. Ajouter colonnes pour les 4 périodes dans marks si nécessaire
        Schema::table('marks', function (Blueprint $table) {
            // Ajouter des colonnes pour stocker les moyennes par période
            if (!Schema::hasColumn('marks', 'p1_avg')) {
                $table->decimal('p1_avg', 5, 2)->nullable()->after('tex3')
                    ->comment('Moyenne période 1');
            }
            if (!Schema::hasColumn('marks', 'p2_avg')) {
                $table->decimal('p2_avg', 5, 2)->nullable()->after('p1_avg')
                    ->comment('Moyenne période 2');
            }
            if (!Schema::hasColumn('marks', 'p3_avg')) {
                $table->decimal('p3_avg', 5, 2)->nullable()->after('p2_avg')
                    ->comment('Moyenne période 3');
            }
            if (!Schema::hasColumn('marks', 'p4_avg')) {
                $table->decimal('p4_avg', 5, 2)->nullable()->after('p3_avg')
                    ->comment('Moyenne période 4');
            }
            if (!Schema::hasColumn('marks', 's1_exam')) {
                $table->integer('s1_exam')->nullable()->after('p4_avg')
                    ->comment('Note examen semestre 1');
            }
            if (!Schema::hasColumn('marks', 's2_exam')) {
                $table->integer('s2_exam')->nullable()->after('s1_exam')
                    ->comment('Note examen semestre 2');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Retour en arrière
        Schema::table('exams', function (Blueprint $table) {
            $table->renameColumn('semester', 'term');
        });

        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'period')) {
                $table->dropColumn('period');
            }
        });

        Schema::table('marks', function (Blueprint $table) {
            $table->dropColumn([
                'p1_avg', 'p2_avg', 'p3_avg', 'p4_avg', 
                's1_exam', 's2_exam'
            ]);
        });
    }
}
