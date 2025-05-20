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
        Schema::create('receipts', function (Blueprint $table) {
           $table->id();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('received_amount', 10, 2);
            $table->decimal('change_amount', 10, 2);
            $table->unsignedBigInteger('cashier_id')->nullable();
            $table->timestamps();

            $table->foreign('cashier_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
