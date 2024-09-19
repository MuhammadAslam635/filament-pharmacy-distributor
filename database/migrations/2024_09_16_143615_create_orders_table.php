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
        Schema::disableForeignKeyConstraints();

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained();
            $table->double('total', 12, 2);
            $table->double('subtotal', 12, 2);
            $table->double('tax', 12, 2);
            $table->enum('status', ['pending', 'dispatch', 'processing', 'devlivered', 'cancelled']);
            $table->date('delivery_date')->nullable();
            $table->date('cancel_date')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
