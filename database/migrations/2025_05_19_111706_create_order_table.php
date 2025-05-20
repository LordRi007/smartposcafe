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
        Schema::create('order', function (Blueprint $table) {
             $table->id();
    $table->string('order_number')->unique();
    $table->string('customer_name')->nullable();
    $table->decimal('total', 10, 2);
    $table->decimal('cash_received', 10, 2);
    $table->decimal('change_given', 10, 2);
      $table->string('status');
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
