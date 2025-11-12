<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class FixBookRequestsStudentIdType extends Migration
{
    /**
     * This migration will:
     * - Find the referenced table/column for book_requests.student_id
     * - Drop the existing FK (if any)
     * - Alter book_requests.student_id to match the referenced column's type (including unsigned)
     * - Recreate the FK with ON DELETE CASCADE / ON UPDATE CASCADE
     *
     * IMPORTANT:
     * - Run `composer require doctrine/dbal` if change() operations are needed or if Laravel complains.
     * - Backup your database before running.
     */
    public function up()
    {
        $db = DB::getDatabaseName();
        $table = 'book_requests';
        $column = 'student_id';

        // 1) find referenced table/column for this FK
        $fk = DB::selectOne("
            SELECT REFERENCED_TABLE_NAME AS ref_table, REFERENCED_COLUMN_NAME AS ref_column, CONSTRAINT_NAME AS fk_name
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ", [$db, $table, $column]);

        if (! $fk) {
            // nothing to do
            return;
        }

        $refTable = $fk->ref_table;
        $refColumn = $fk->ref_column;
        $fkName = $fk->fk_name;

        // 2) get referenced column type and nullability
        $refInfo = DB::selectOne("
            SELECT COLUMN_TYPE, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT, CHARACTER_MAXIMUM_LENGTH
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
            LIMIT 1
        ", [$db, $refTable, $refColumn]);

        if (! $refInfo) {
            throw new \Exception('Referenced column info not found: ' . $refTable . '.' . $refColumn);
        }

        $columnType = $refInfo->COLUMN_TYPE; // e.g. bigint(20) unsigned
        $isNullable = ($refInfo->IS_NULLABLE === 'YES');

        // 3) Drop existing FK on child if exists
        try {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fkName}`;");
        } catch (\Exception $e) {
            // ignore if cannot drop
        }

        // 4) Modify the child column to match referenced column type
        // Build ALTER TABLE ... MODIFY statement. Preserve nullability.
        $nullSql = $isNullable ? 'NULL' : 'NOT NULL';

        try {
            // If COLUMN_TYPE contains 'unsigned' we include it directly
            DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` {$columnType} {$nullSql};");
        } catch (\Exception $e) {
            // Fallback: try using Doctrine's change() if available
            try {
                Schema::table($table, function (Blueprint $t) use ($column) {
                    $t->unsignedBigInteger($column)->change();
                });
            } catch (\Exception $ex) {
                throw $ex;
            }
        }

        // 5) Recreate FK constraint
        $newFkName = $fkName ?: "{$table}_{$column}_foreign";
        try {
            DB::statement("ALTER TABLE `{$table}` ADD CONSTRAINT `{$newFkName}` FOREIGN KEY (`{$column}`) REFERENCES `{$refTable}`(`{$refColumn}`) ON DELETE CASCADE ON UPDATE CASCADE;");
        } catch (\Exception $e) {
            // ignore if cannot add FK
        }
    }

    public function down()
    {
        // best-effort: drop the FK and change column back to INT UNSIGNED
        $table = 'book_requests';
        $column = 'student_id';
        $db = DB::getDatabaseName();

        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME AS fk_name
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ", [$db, $table, $column]);

        if ($fk && isset($fk->fk_name)) {
            try {
                DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fk->fk_name}`;");
            } catch (\Exception $e) {
                // ignore
            }
        }

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
