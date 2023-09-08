<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
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
    public function index(Request $request)
    {
        $search = $request->search;
        $categorie_id = $request->categorie_id;
        $products = Product::filterProduct($search, $categorie_id)->orderBy('id', 'DESC')->paginate(30);
        return response()->json([
            'message' => 200,
            'total' => $products->total(),
            'productos' => ProductCollection::make($products),
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
        $is_product = Product::where('name', $request->name)->first();
        if ($is_product) {
            return response()->json([
                'message' => 403
            ]);
        }   
        
        $request->request->add(['slug' => Str::slug($request->name)]);
        if ($request->hasFile('image_file')) {
            $path = Storage::putFile('productos', $request->file('image_file'));
            $request->request->add(['imagen' => $path]);
        }

        $product = Product::create($request->all());

        foreach ($request->file('files') as $key => $file) {
            $extension = $file->getClientOriginalExtension();
            $size = $file->getSize();
            $file_name = $file->getClientOriginalName();

            $path = Storage::putFile('productos', $file);
            ProductImage::create([                
                'file_name' => $file_name,
                'imagen' => $path,
                'size' => $size,
                'type' => $extension,
                'product_id' => $product->id,
            ]);
        }
        
        return response()->json([
            'message' => 200,
            //'product' => $product,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([            
            'producto' => ProductResource::make($product),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $is_product = Product::where('id', '<>', $id)->where('name', $request->name)->first();
        if ($is_product) {
            return response()->json([
                'message' => 403
            ]);
        }   
        
        $product = Product::findOrFail($id);

        $request->request->add(['slug' => Str::slug($request->name)]);
        if ($request->hasFile('image_file')) {
            $path = Storage::putFile('productos', $request->file('image_file'));
            $request->request->add(['imagen' => $path]);
        }

        $product->update($request->all());

        
        
        return response()->json([
            'message' => 200,
            'product' => $product,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
