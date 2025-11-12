<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FreshStart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop existing foreign key constraints
        try {
            Schema::table('payments', function (Blueprint $table) {
                                    // Safely drop foreign key for column 'my_class_id' if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'payments' AND COLUMN_NAME = 'my_class_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('payments', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            });
        } catch (\Exception $e) {
            // Ignore if the foreign key doesn't exist
        }

        // Add the code column to users table if it doesn't exist
        if (!Schema::hasColumn('users', 'code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('code')->unique()->after('id')->nullable();
            });
        }

        // Add the my_class_id column to payments table if it doesn't exist
        if (!Schema::hasColumn('payments', 'my_class_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->unsignedInteger('my_class_id')->nullable()->after('id');
            });
        }

        // Add the foreign key constraint with a unique name
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('my_class_id', 'fk_payments_my_class_id')
                  ->references('id')
                  ->on('my_classes')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the foreign key constraint
        Schema::table('payments', function (Blueprint $table) {
                                // Safely drop foreign by constraint name if exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'payments' AND CONSTRAINT_NAME = 'fk_payments_my_class_id' AND CONSTRAINT_TYPE='FOREIGN KEY'");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('payments', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

        });

        // Remove the columns if needed
        if (Schema::hasColumn('users', 'code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('code');
            });
        }

        if (Schema::hasColumn('payments', 'my_class_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('my_class_id');
            });
        }
    }
}
