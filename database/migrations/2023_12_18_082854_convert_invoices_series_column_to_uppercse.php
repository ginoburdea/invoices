<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('old_series')->nullable();
        });
        DB::statement('UPDATE invoices SET old_series = series');
        DB::statement('UPDATE invoices SET series = UPPER(series)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('UPDATE invoices SET series = old_series');
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('old_series');
        });
    }
};
