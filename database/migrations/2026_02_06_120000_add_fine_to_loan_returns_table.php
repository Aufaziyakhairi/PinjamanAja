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
        Schema::table('loan_returns', function (Blueprint $table) {
            $table->unsignedInteger('fine_days')->default(0)->after('received_at');
            $table->unsignedBigInteger('fine_amount')->default(0)->after('fine_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_returns', function (Blueprint $table) {
            $table->dropColumn(['fine_days', 'fine_amount']);
        });
    }
};
