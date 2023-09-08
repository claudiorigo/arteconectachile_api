<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSize extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'product_id',        
    ];
    
    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function product_colors_sizes(){
        return $this->hasMany(ProductColorSize::class);
    }
}
