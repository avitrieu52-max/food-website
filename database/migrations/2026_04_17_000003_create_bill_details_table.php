<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bill');
            $table->unsignedBigInteger('id_product');
            $table->integer('quantity');
            $table->decimal('unit_price', 16, 2);
            $table->timestamps();

            $table->foreign('id_bill')->references('id')->on('bills')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_details');
    }
};
