<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('my_classes', function (Blueprint $table) {
            $table->string('academic_level')->nullable()->after('class_type_id');
            $table->string('academic_option')->nullable()->after('academic_level');
            $table->string('division')->nullable()->after('academic_option');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('my_classes', function (Blueprint $table) {
            $table->dropColumn(['academic_level', 'academic_option', 'division']);
        });
    }
};
