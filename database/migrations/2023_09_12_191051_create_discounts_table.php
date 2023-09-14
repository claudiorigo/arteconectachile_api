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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 150);
            $table->tinyInteger('type_discount', false)->unsigned()->default(1)->comment('1 Porcentaje, 2 Monto');
            $table->double('discount');            
            $table->tinyInteger('state', false)->unsigned()->default(1)->comment('1 Activo, 2 Desactivo');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->tinyInteger('type', false)->unsigned()->comment('1 Producto, 2 Categoria');
            $table->timestamps();
            $table->softDeletes();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
