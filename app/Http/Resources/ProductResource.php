<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'sku' => $this->resource->sku,
            'description_short' => $this->resource->description_short,
            'description' => $this->resource->description,
            'price_pesos' => $this->resource->price_pesos,
            'price_usd' => $this->resource->price_usd,
            'state' => $this->resource->state,
            'imagen' => config('app.url') . 'storage/' . $this->resource->imagen,
            'stock' => $this->resource->stock,
            'tags' => $this->resource->tags,
            'tags_array' => $this->resource->tags ? explode(',', $this->resource->tags) : [],
            'checked_intentario' => $this->resource->type_inventario,
            'categorie_id' => $this->resource->categorie_id,
            'categorie' => [
                'id' => $this->resource->categorie->id,
                'name' => $this->resource->categorie->name,
                'icono' => $this->resource->categorie->icono,
            ],

            'images' => $this->resource->images->map(function($image){
                return [
                    'id' => $image->id,
                    'file_name' => $image->file_name,
                    'imagen' => config('app.url') . 'storage/' . $image->imagen,
                    'size' => $image->size,
                    'type' => $image->type,                
                ];
            }),

            'size' => $this->resource->sizes->map(function($size) {
                return [
                    'id' => $size->id,
                    'name' => $size->name,
                    'total' => $size->product_colors_sizes->sum('stock'),
                    'variaciones' => $size->product_colors_sizes->map(function($var) {
                        return [
                            'id' => $var->id,
                            'product_color_id' => $var->product_color_id,
                            'product_color' => $var->product_color,
                            'stock' => $var->stock,
                        ];
                    }),
                ];
            }),
            //'product_inventario' =>
        ];
    }
}
