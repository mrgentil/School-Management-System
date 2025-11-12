<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateUserForeignKeysToBigint extends Migration
{
    /**
     * NOTE: Before running this migration, install doctrine/dbal:
     *   composer require doctrine/dbal
     *
     * This migration tries to be defensive: for each table/column pair it:
     *  - drops an existing FK referencing users(id) for that column (if any),
     *  - modifies the column to BIGINT UNSIGNED,
     *  - re-creates the FK referencing users(id) with cascade on delete/update.
     *
     * It only acts when the column exists.
     */
    protected $map = [
        // Add all table => column pairs that reference users.id in your app.
        // I included the most common/likely ones based on your project history.
        'book_loans' => ['user_id'],
        // 'book_requests' => ['user_id', 'student_id'],
        'book_loans' => ['user_id'],
        'book_loans_histories' => ['user_id'],
        'book_loans_logs' => ['user_id'],
        'payments' => ['user_id'],
        'payment_records' => ['user_id'],
        'receipts' => ['user_id'],
        'time_tables' => ['user_id'],
        'student_records' => ['user_id'],
        'staff_records' => ['user_id'],
        'sections' => ['teacher_id'],
        'attendances' => ['user_id', 'student_id'],
        'assignments' => ['user_id'],
        'messages' => ['user_id'],
        'notifications' => ['user_id'],
        // Add more pairs as needed...
    ];

    public function up()
    {
        // Ensure DBAL is available when using change()
        foreach ($this->map as $table => $columns) {
            foreach ($columns as $column) {
                if (!Schema::hasTable($table)) {
                    // table doesn't exist, skip
                    continue;
                }
                if (!Schema::hasColumn($table, $column)) {
                    // column doesn't exist, skip
                    continue;
                }

                // 1) Drop existing FK for this column that references users(id)
                $fkName = $this->getForeignKeyName($table, $column);
                if ($fkName) {
                    try {
                        DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fkName}`;");
                    } catch (\Exception $e) {
                        // ignore errors dropping FK
                    }
                }

                // 2) Modify the column to BIGINT UNSIGNED
                try {
                    // Use raw ALTER TABLE to avoid some DBAL quirks
                    DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` BIGINT UNSIGNED;");
                } catch (\Exception $e) {
                    // fallback to using change() if available
                    try {
                        Schema::table($table, function (Blueprint $tableBlueprint) use ($column) {
                            $tableBlueprint->unsignedBigInteger($column)->change();
                        });
                    } catch (\Exception $ex) {
                        // log / ignore - best effort
                        // You may need to run `composer require doctrine/dbal` before this migration.
                        throw $ex;
                    }
                }

                // 3) Recreate the foreign key constraint
                $newFkName = "{$table}_{$column}_foreign";
                try {
                    DB::statement("ALTER TABLE `{$table}` ADD CONSTRAINT `{$newFkName}` FOREIGN KEY (`{$column}`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;");
                } catch (\Exception $e) {
                    // ignore if cannot add FK
                }
            }
        }
    }

    public function down()
    {
        // Attempt to revert back to INT UNSIGNED (best-effort).
        foreach ($this->map as $table => $columns) {
            foreach ($columns as $column) {
                if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
                    continue;
                }

                // drop FK if exists
                $fkName = $this->getForeignKeyName($table, $column);
                if ($fkName) {
                    try {
                        DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fkName}`;");
                    } catch (\Exception $e) {
                        // ignore
                    }
                }

                // change back to INT UNSIGNED
                try {
                    DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` INT UNSIGNED;");
                } catch (\Exception $e) {
                    try {
                        Schema::table($table, function (Blueprint $t) use ($column) {
                            $t->unsignedInteger($column)->change();
                        });
                    } catch (\Exception $ex) {
                        // ignore
                    }
                }
            }
        }
    }

    /**
     * Return the foreign key name that references users(id) for a given table.column
     * or null if none found.
     */
    protected function getForeignKeyName($table, $column)
    {
        $db = DB::getDatabaseName();
        $row = DB::selectOne("
            SELECT CONSTRAINT_NAME AS fk
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME = 'users'
            LIMIT 1
        ", [$db, $table, $column]);

        return $row ? $row->fk : null;
    }
}
