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
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('shop');
            $table->string('slug')->unique()->nullable();
            $table->text('address')->nullable();
            $table->integer('orders');
            $table->double('total', 12, 2);
            $table->double('paid', 12, 2);
            $table->enum('status', ["approved","pending","block"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
