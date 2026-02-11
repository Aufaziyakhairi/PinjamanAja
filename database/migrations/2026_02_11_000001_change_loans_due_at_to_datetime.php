<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('loans') || !Schema::hasColumn('loans', 'due_at')) {
            return;
        }

        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE loans MODIFY due_at DATETIME NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE loans ALTER COLUMN due_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING due_at::timestamp');
        }

        // sqlite: no-op (fresh installs will use updated create migration).
    }

    public function down(): void
    {
        if (!Schema::hasTable('loans') || !Schema::hasColumn('loans', 'due_at')) {
            return;
        }

        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE loans MODIFY due_at DATE NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE loans ALTER COLUMN due_at TYPE DATE USING due_at::date');
        }

        // sqlite: no-op.
    }
};
