<?php

namespace App\Http\Controllers;

use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductSizeController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Para acceder necesitas estar autenticado.
        $this->middleware('auth:api');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products_sizes = ProductSize::orderBy('id', 'DESC')->get();
        return response()->json([
            'message' => 200,
            'products_sizes' => $products_sizes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductSize $productSize)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductSize $productSize)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product_color_size = ProductSize::where('id', '<>', $id)->where('name', $request->name)->first();
        if ($product_color_size) {
            return response()->json([
                'message' => 403,
                'message_error' => 'Este nombre de tamaño ya existe product_size.',
            ]);
        }

        $product_size = ProductSize::findOrFail($id);
        $product_size->update($request->all());
        return response()->json([
            'message' => 200,
            'product_size' => [
                'id' => $product_size->id,
                'name' => $product_size->name,
                'total' => $product_size->product_colors_sizes->sum('stock'),
                'variaciones' => $product_size->product_colors_sizes->map(function($var) {
                    return [
                        'id' => $var->id,
                        'product_color_id' => $var->product_color_id,
                        'product_color' => $var->product_color,
                        'stock' => $var->stock,
                    ];
                }),
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product_size = ProductSize::findOrFail($id);
        $product_size->delete();
        return response()->json([
            'message' => 200,
        ]);
    }
}
