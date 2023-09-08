<?php

namespace App\Http\Controllers;

use App\Models\ProductColorSize;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductColorSizeController extends Controller
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
        //
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
        if (!$request->product_size_id) {
            $product_color_size = ProductSize::where('name', $request->product_size_new)->first();
            if ($product_color_size) {
                return response()->json([
                    'message' => 403,
                    'message_error' => 'Este nombre de tamaño ya existe product_size.',
                ]);
            }
            $product_size = ProductSize::create([
                'name' => $request->product_size_new,
                'product_id' => $request->product_id,
            ]);
        }else{
            $product_size = ProductSize::findOrFail($request->product_size_id);
        }        
        
        $product_color_size = ProductColorSize::where('product_color_id', $request->product_color_id)->where('product_size_id', $product_size->id)->first();
        if ($product_color_size) {
            return response()->json([
                'message' => 403,
                'message_error' => 'Esta configuración ya existe product_color_size.',
            ]);
        }

        $product_color_size = ProductColorSize::create([
            'product_color_id' => $request->product_color_id,
            'product_size_id' => $product_size->id,
            'stock' => $request->stock,
        ]);

        return response()->json([
            'message' => 200,
            'product_color_size' => [
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
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductColorSize $productColorSize)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductColorSize $productColorSize)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product_color_size = ProductColorSize::where('id', '<>', $id)->where('product_color_id', $request->product_color_id)->where('product_size_id', $request->product_size_id)->first();
        if ($product_color_size) {
            return response()->json([
                'message' => 403,
                'message_error' => 'Esta configuración ya existe product_color_size.',
            ]);
        }

        $product_color_size = ProductColorSize::findOrFail($id);
        $product_color_size->update($request->all());
        return response()->json([
            'message' => 200,
            'product_color_size' => [
                'id' => $product_color_size->id,
                'product_color_id' => $product_color_size->product_color_id,
                'product_color' => $product_color_size->product_color,
                'stock' => $product_color_size->stock,
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product_size = ProductColorSize::findOrFail($id);
        $product_size->delete();
        return response()->json([
            'message' => 200,
        ]);
    }
}
