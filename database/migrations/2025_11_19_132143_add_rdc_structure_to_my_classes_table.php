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
            $table->unsignedBigInteger('academic_level_id')->nullable()->after('class_type_id');
            $table->unsignedBigInteger('academic_option_id')->nullable()->after('academic_level_id');
            $table->string('division')->nullable()->after('academic_option_id'); // A, B, C, D
            
            $table->foreign('academic_level_id')->references('id')->on('academic_levels')->onDelete('set null');
            $table->foreign('academic_option_id')->references('id')->on('academic_options')->onDelete('set null');
            
            $table->index(['academic_level_id', 'academic_option_id', 'division']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('my_classes', function (Blueprint $table) {
            $table->dropForeign(['academic_level_id']);
            $table->dropForeign(['academic_option_id']);
            $table->dropIndex(['academic_level_id', 'academic_option_id', 'division']);
            $table->dropColumn(['academic_level_id', 'academic_option_id', 'division']);
        });
    }
};
