<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFks extends Migration
{

    public function up()
    {
        Schema::table('lgas', function (Blueprint $table) {
            // FK for column 'state_id' moved to final migration (auto-generated).
// Original: $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            // FK for column 'state_id' moved to final migration (auto-generated).
// Original: $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            // FK for column 'lga_id' moved to final migration (auto-generated).
// Original: $table->foreign('lga_id')->references('id')->on('lgas')->onDelete('set null');
            // FK for column 'bg_id' moved to final migration (auto-generated).
// Original: $table->foreign('bg_id')->references('id')->on('blood_groups')->onDelete('set null');
            // FK for column 'nal_id' moved to final migration (auto-generated).
// Original: $table->foreign('nal_id')->references('id')->on('nationalities')->onDelete('set null');
        });

        Schema::table('my_classes', function (Blueprint $table) {
            // FK for column 'class_type_id' moved to final migration (auto-generated).
// Original: $table->foreign('class_type_id')->references('id')->on('class_types')->onDelete('set null');
        });

        Schema::table('sections', function (Blueprint $table) {
            // FK for column 'my_class_id' moved to final migration (auto-generated).
// Original: $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            // FK for column 'teacher_id' moved to final migration (auto-generated).
// Original: $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('subjects', function (Blueprint $table) {
            // FK for column 'teacher_id' moved to final migration (auto-generated).
// Original: $table->foreign('teacher_id')->references('id')->on('users');
            // FK for column 'my_class_id' moved to final migration (auto-generated).
// Original: $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
        });

        Schema::table('student_records', function (Blueprint $table) {
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // FK for column 'my_class_id' moved to final migration (auto-generated).
// Original: $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            // FK for column 'section_id' moved to final migration (auto-generated).
// Original: $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            // FK for column 'my_parent_id' moved to final migration (auto-generated).
// Original: $table->foreign('my_parent_id')->references('id')->on('users')->onDelete('set null');
            // FK for column 'dorm_id' moved to final migration (auto-generated).
// Original: $table->foreign('dorm_id')->references('id')->on('dorms')->onDelete('set null');
        });

        Schema::table('marks', function (Blueprint $table) {
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            // FK for column 'my_class_id' moved to final migration (auto-generated).
// Original: $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            // FK for column 'section_id' moved to final migration (auto-generated).
// Original: $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            // FK for column 'subject_id' moved to final migration (auto-generated).
// Original: $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            // FK for column 'exam_id' moved to final migration (auto-generated).
// Original: $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            // FK for column 'grade_id' moved to final migration (auto-generated).
// Original: $table->foreign('grade_id')->references('id')->on('grades')->onDelete('set null');
        });

        Schema::table('grades', function (Blueprint $table) {
            // FK for column 'class_type_id' moved to final migration (auto-generated).
// Original: $table->foreign('class_type_id')->references('id')->on('class_types')->onDelete('cascade');
        });

        Schema::table('pins', function (Blueprint $table) {
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('exam_records', function (Blueprint $table) {
            // FK for column 'exam_id' moved to final migration (auto-generated).
// Original: $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            // FK for column 'my_class_id' moved to final migration (auto-generated).
// Original: $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            // FK for column 'section_id' moved to final migration (auto-generated).
// Original: $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });

        Schema::table('books', function (Blueprint $table) {
            // FK for column 'my_class_id' moved to final migration (auto-generated).
// Original: $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
        });

        // Schema::table('book_requests', function (Blueprint $table) {
        //     // FK for column 'book_id' moved to final migration (auto-generated).
// Original: $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        //     // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        // });

        Schema::table('staff_records', function (Blueprint $table) {
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('payments', function (Blueprint $table) {
            // FK for column 'my_class_id' moved to final migration (auto-generated).
// Original: $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
        });

        Schema::table('payment_records', function (Blueprint $table) {
            // FK for column 'payment_id' moved to final migration (auto-generated).
// Original: $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('receipts', function (Blueprint $table) {
            // FK for column 'pr_id' moved to final migration (auto-generated).
// Original: $table->foreign('pr_id')->references('id')->on('payment_records')->onDelete('cascade');
        });

        Schema::table('time_table_records', function (Blueprint $table) {
            // FK for column 'exam_id' moved to final migration (auto-generated).
// Original: $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            // FK for column 'my_class_id' moved to final migration (auto-generated).
// Original: $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
        });

        Schema::table('time_slots', function (Blueprint $table) {
            // FK for column 'ttr_id' moved to final migration (auto-generated).
// Original: $table->foreign('ttr_id')->references('id')->on('time_table_records')->onDelete('cascade');
        });

        Schema::table('time_tables', function (Blueprint $table) {
            // FK for column 'ttr_id' moved to final migration (auto-generated).
// Original: $table->foreign('ttr_id')->references('id')->on('time_table_records')->onDelete('cascade');
            // FK for column 'ts_id' moved to final migration (auto-generated).
// Original: $table->foreign('ts_id')->references('id')->on('time_slots')->onDelete('cascade');
            // FK for column 'subject_id' moved to final migration (auto-generated).
// Original: $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });

    }

    public function down()
    {

    }
}
