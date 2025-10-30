<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToBookRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_requests', function (Blueprint $table) {
            $table->enum('status', ['en_attente', 'approuve', 'refuse'])->default('en_attente')->after('book_id');
            $table->text('reponse')->nullable()->after('status');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('student_id');
            $table->timestamp('date_demande')->useCurrent()->after('approved_by');
            $table->timestamp('date_traitement')->nullable()->after('date_demande');
            $table->boolean('is_notified')->default(false)->after('date_traitement');
            $table->string('notification_type')->nullable()->after('is_notified');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_requests', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'status',
                'reponse',
                'approved_by',
                'date_demande',
                'date_traitement',
                'is_notified',
                'notification_type'
            ]);
        });
    }
}
