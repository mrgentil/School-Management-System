<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixBookLoansUserIdType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop existing foreign key constraint if it exists
        if (Schema::hasTable('book_loans')) {
            Schema::table('book_loans', function ($table) {
                // Safely drop foreign key for column 'user_id' if it exists
                $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                if ($constraint && isset($constraint->name)) {
                    // Drop FK within the same callback to avoid nested Schema::table and type-hint conflicts
                    $table->dropForeign($constraint->name);
                }
            });

            // Change the column type to match users.id (unsignedInteger)
            DB::statement('ALTER TABLE book_loans MODIFY user_id INT UNSIGNED NULL');

            // Re-add the foreign key constraint
            Schema::table('book_loans', function ($table) {
                // FK for column 'user_id' moved to final migration (auto-generated).
                // Original kept as comment intentionally.
            });
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This is the reverse operation - change back to bigint if needed
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        if (Schema::hasTable('book_loans')) {
            Schema::table('book_loans', function ($table) {
                // Safely drop foreign key for column 'user_id' if it exists
                $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                if ($constraint && isset($constraint->name)) {
                    $table->dropForeign($constraint->name);
                }
            });

            DB::statement('ALTER TABLE book_loans MODIFY user_id BIGINT UNSIGNED NULL');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
