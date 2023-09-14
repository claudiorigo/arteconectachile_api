<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCategorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'discount_id',
        'categorie_id',        
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    /* public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    } */
}
