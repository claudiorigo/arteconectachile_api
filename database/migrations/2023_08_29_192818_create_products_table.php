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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 200);
            $table->string('sku', 50);
            $table->text('description_short')->nullable();
            $table->longText('description')->collation('utf8_general_ci');                        
            $table->double('price_pesos');
            $table->double('price_usd')->nullable();
            $table->tinyInteger('state', false)->unsigned()->default(1)->comment('1 Demo, 2 Publico, 3 Bloqueado');
            $table->string('imagen');
            $table->double('stock')->nullable();
            $table->text('tags')->nullable();
            $table->tinyInteger('type_inventario', false)->unsigned()->default(1)->comment('1 Individual Stock, 2 Multiple Stock');            
            $table->unsignedBigInteger('categorie_id')->default(0)->unsigned();
            $table->foreign('categorie_id')->references('id')->on('categories')->onDelete('cascade');            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
