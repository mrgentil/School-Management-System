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
        // Skip this migration as the columns already exist
        // Schema::table('book_requests', function (Blueprint $table) {
        //     $table->enum('status', ['en_attente', 'approuve', 'refuse'])->default('en_attente')->after('book_id');
        //     $table->text('reponse')->nullable()->after('status');
        //     $table->unsignedBigInteger('approved_by')->nullable()->after('student_id');
        //     $table->timestamp('date_demande')->useCurrent()->after('approved_by');
        //     $table->timestamp('date_traitement')->nullable()->after('date_demande');
        //     $table->boolean('is_notified')->default(false)->after('date_traitement');
        //     $table->string('notification_type')->nullable()->after('is_notified');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_requests', function (Blueprint $table) {
                                // Safely drop foreign key for column 'approved_by' if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_requests' AND COLUMN_NAME = 'approved_by' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_requests', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

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
