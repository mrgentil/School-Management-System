<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pins', function (Blueprint $table) {
            // Ajouter les nouveaux champs si ils n'existent pas
            if (!Schema::hasColumn('pins', 'type')) {
                $table->string('type')->default('bulletin')->after('code'); // bulletin, exam, result
            }
            if (!Schema::hasColumn('pins', 'year')) {
                $table->string('year')->nullable()->after('type'); // Année scolaire
            }
            if (!Schema::hasColumn('pins', 'period')) {
                $table->tinyInteger('period')->nullable()->after('year'); // 1-4 pour périodes
            }
            if (!Schema::hasColumn('pins', 'semester')) {
                $table->tinyInteger('semester')->nullable()->after('period'); // 1-2 pour semestres
            }
            if (!Schema::hasColumn('pins', 'my_class_id')) {
                $table->unsignedInteger('my_class_id')->nullable()->after('semester');
            }
            if (!Schema::hasColumn('pins', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('my_class_id'); // Prix du PIN
            }
            if (!Schema::hasColumn('pins', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('price'); // Date d'expiration
            }
            if (!Schema::hasColumn('pins', 'max_uses')) {
                $table->tinyInteger('max_uses')->default(1)->after('expires_at'); // Nombre max d'utilisations
            }
            if (!Schema::hasColumn('pins', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('student_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pins', function (Blueprint $table) {
            $columns = ['type', 'year', 'period', 'semester', 'my_class_id', 'price', 'expires_at', 'max_uses', 'created_by'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pins', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
