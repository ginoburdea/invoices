<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->boolean('draft');
            $table->date('paid_at')->nullable();

            $table->string('series');
            $table->integer('number')->nullable();

            $table->decimal('subtotal');
            $table->decimal('tax');

            $table->foreignId('client_id');
            $table->string('client_name');
            $table->string('client_tax_id');
            $table->string('client_address');

            $table->string('vendor_name');
            $table->string('vendor_tax_id');
            $table->string('vendor_address');

            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
