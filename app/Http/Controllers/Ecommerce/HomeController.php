<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $sliders = Slider::orderBy('id', 'DESC')->get()->map(function($slider) {
            return [
                'id' => $slider->id,
                'name' => $slider->name,
                'slug' => $slider->url,                    
                'imagen' => config('app.url') . 'storage/' . $slider->imagen,
            ];
        });
        $categories = Categorie::orderBy('id', 'DESC')->take(4)->get();

        $group_categories_products = collect([]);
        foreach ($categories as $key => $categorie) {
            $products = $categorie->products->take(3);
            $group_categories_products->push([
                'id' => $categorie->id,
                'name' => $categorie->name,
                'products' => $products->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price_pesos' => $product->price_pesos,
                        'price_usd' => $product->price_usd,
                        'imagen' => config('app.url') . 'storage/' . $product->imagen,
                    ];
                }),
            ]);
        }

        $products_random_a = Product::inRandomOrder()->limit(4)->get()->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price_pesos' => $product->price_pesos,
                'price_usd' => $product->price_usd,
                'imagen' => config('app.url') . 'storage/' . $product->imagen,
            ];
        });
        $products_random_b = Product::inRandomOrder()->limit(8)->get()->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price_pesos' => $product->price_pesos,
                'price_usd' => $product->price_usd,
                'imagen' => config('app.url') . 'storage/' . $product->imagen,
            ];
        });

        return response()->json([
            'sliders' => $sliders,
            'group_categories_products' => $group_categories_products,
            'products_random_a' => $products_random_a,
            'products_random_b' => $products_random_b,
        ]);
    }
}
