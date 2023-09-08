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
        Schema::create('product_color_sizes', function (Blueprint $table) {
            $table->id();
            $table->double('stock');
            $table->unsignedBigInteger('product_color_id')->default(0)->unsigned();
            $table->foreign('product_color_id')->references('id')->on('product_colors');
            $table->unsignedBigInteger('product_size_id')->default(0)->unsigned();
            $table->foreign('product_size_id')->references('id')->on('product_sizes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_color_sizes');
    }
};
