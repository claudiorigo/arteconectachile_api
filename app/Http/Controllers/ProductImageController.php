<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
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
        $file = $request->file('file');
        if ($request->hasFile('file')) {
            $extension = $file->getClientOriginalExtension();
            $size = $file->getSize();
            $file_name = $file->getClientOriginalName();

            $path = Storage::putFile('productos', $file);
            $product_imagen = ProductImage::create([                
                'file_name' => $file_name,
                'imagen' => $path,
                'size' => $size,
                'type' => $extension,
                'product_id' => $request->product_id,
            ]);
        }
        
        return response()->json([
            'product_imagen' => [
                'id' => $product_imagen->id,
                'file_name' => $product_imagen->file_name,
                'imagen' => config('app.url') . 'storage/' . $product_imagen->imagen,
                'size' => $product_imagen->size,
                'type' => $product_imagen->type,                
            ]            
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductImage $productImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductImage $productImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductImage $productImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product_imagen = ProductImage::findOrFail($id);
        if($product_imagen->imagen){
            Storage::delete($product_imagen->imagen);
        }
        $product_imagen->delete();
        return response()->json([
            'message' => 200
        ]);
    }
}
