<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('from_class');
            $table->unsignedInteger('from_section');
            $table->unsignedInteger('to_class');
            $table->unsignedInteger('to_section');
            $table->tinyInteger('grad');
            $table->string('from_session');
            $table->string('to_session');
            $table->string('status');
            $table->timestamps();
        });

        Schema::table('promotions', function (Blueprint $table) {
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            // FK for column 'from_class' moved to final migration (auto-generated).
// Original: $table->foreign('from_class')->references('id')->on('my_classes')->onDelete('cascade');
            // FK for column 'from_section' moved to final migration (auto-generated).
// Original: $table->foreign('from_section')->references('id')->on('sections')->onDelete('cascade');
            // FK for column 'to_section' moved to final migration (auto-generated).
// Original: $table->foreign('to_section')->references('id')->on('sections')->onDelete('cascade');
            // FK for column 'to_class' moved to final migration (auto-generated).
// Original: $table->foreign('to_class')->references('id')->on('my_classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}
