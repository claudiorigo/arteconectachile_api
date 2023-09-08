<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description_short',
        'description',
        'price_pesos',
        'price_usd',
        'state',
        'imagen',
        'stock',
        'tags',
        'type_inventario',
        'categorie_id'
    ];

    public function categorie(){
        return $this->belongsTo(Categorie::class);
    }

    public function images(){
        return $this->hasMany(ProductImage::class);
    }

    public function sizes(){
        return $this->hasMany(ProductSize::class);
    }

    public function scopefilterProduct($query, $search, $categorie_id){
        if ($search) {
            $query->where('name', 'like', '%' . $search .'%');
        }
        if ($categorie_id) {
            $query->where('categorie_id', $categorie_id);
        }        
        return $query;
    }
}
