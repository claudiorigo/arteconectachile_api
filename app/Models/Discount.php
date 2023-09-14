<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'type_discount',
        'discount',
        'state',
        'start_date',
        'end_date',
        'type',            // 1 productos, 2 categorías
    ];

    public function discountProducts()
    {
        return $this->hasMany(DiscountProduct::class);
    }

    public function discountCategories()
    {
        return $this->hasMany(DiscountCategorie::class);
    }

    /* public function products()
    {
        return $this->hasMany(DiscountProduct::class);
    }

    public function categories()
    {
        return $this->hasMany(DiscountCategorie::class);
    } */

    public function scopeValidateDiscount($query, $request, $product_array=[], $categorie_array=[]){
        $query->where('type', $request->type);

        if ($request->type == 1) { //FILTRAR POR PRODUCTO
            $query->whereHas('discountProducts', function($query_fk) use($product_array){
                return $query_fk->whereIn('product_id', $product_array);
            });
        }

        if ($request->type == 2) { //FILTRAR POR CATEGORÍA
            $query->whereHas('discountCategories', function($query_fk) use($categorie_array){
                return $query_fk->whereIn('categorie_id', $categorie_array);
            });
        }

        return $query;
    }
}
