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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 150);
            $table->tinyInteger('type_discount', false)->unsigned()->default(1)->comment('1 Porcentaje, 2 Monto');
            $table->double('discount');
            $table->tinyInteger('type_count', false)->unsigned()->default(1)->comment('1 Ilimitado, 2 Limitado');
            $table->double('num_use')->default(0);
            $table->tinyInteger('state', false)->unsigned()->default(1)->comment('1 Activo, 2 Desactivo');
            $table->string('products')->nullable();
            $table->string('categories')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
