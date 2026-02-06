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
        Schema::table('tools', function (Blueprint $table) {
            if (Schema::hasColumn('tools', 'code')) {
                $table->dropUnique(['code']);
                $table->dropColumn('code');
            }

            if (Schema::hasColumn('tools', 'stock_total')) {
                $table->dropColumn('stock_total');
            }

            if (Schema::hasColumn('tools', 'stock_available')) {
                $table->dropColumn('stock_available');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            if (!Schema::hasColumn('tools', 'code')) {
                $table->string('code')->unique()->after('category_id');
            }

            if (!Schema::hasColumn('tools', 'stock_total')) {
                $table->unsignedInteger('stock_total')->default(0)->after('description');
            }

            if (!Schema::hasColumn('tools', 'stock_available')) {
                $table->unsignedInteger('stock_available')->default(0)->after('stock_total');
            }
        });
    }
};
