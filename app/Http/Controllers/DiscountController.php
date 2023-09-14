<?php

namespace App\Http\Controllers;

use App\Http\Resources\DiscountCollection;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Models\DiscountCategorie;
use App\Models\DiscountProduct;
use Illuminate\Http\Request;

class DiscountController extends Controller
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
        $discounts = Discount::where('code', 'like', '%'. $request->search .'%')->orderBy('id', 'DESC')->get();        
        return response()->json([
            'message' => 200,
            'descuentos' => DiscountCollection::make($discounts),            
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
        $product_array = [];
        $categorie_array = [];

        if($request->type == 1){
            foreach ($request->products_selected as $key => $product) {
                array_push($product_array, $product['id']);
            }
        }

        if($request->type == 2){
            foreach ($request->categories_selected as $key => $categorie) {
                array_push($categorie_array, $categorie['id']);
            }
        }
        
        $IS_EXISTS_START_DATE = Discount::ValidateDiscount($request, $product_array, $categorie_array)->whereBetween('start_date', [$request->start_date, $request->end_date])->first();
        $IS_EXISTS_END_DATE = Discount::ValidateDiscount($request, $product_array, $categorie_array)->whereBetween('end_date', [$request->start_date, $request->end_date])->first();

        if ($IS_EXISTS_START_DATE || $IS_EXISTS_END_DATE) {
            return response()->json([
                'message' => 403,
                'message_error' => 'No se puede registrar descuento.',
            ]);
        }

        $request->request->add(['code' => uniqid()]);
        $discount = Discount::create($request->all());

        if($request->type == 1){
            foreach ($product_array as $key => $product_id) {
                DiscountProduct::create([
                    'discount_id' => $discount->id,
                    'product_id' => $product_id,
                ]);
            }
        }

        if($request->type == 2){
            foreach ($categorie_array as $key => $categorie_id) {
                DiscountCategorie::create([
                    'discount_id' => $discount->id,
                    'categorie_id' => $categorie_id,
                ]);
            }
        }
        
        return response()->json([
            'message' => 200,
            'descuento' => $discount,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $discount = Discount::findOrFail($id);
        return response()->json([
            'message' => 200,
            'descuento' => DiscountResource::make($discount),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product_array = [];
        $categorie_array = [];

        if($request->type == 1){
            foreach ($request->products_selected as $key => $product) {
                array_push($product_array, $product['id']);
            }
        }

        if($request->type == 2){
            foreach ($request->categories_selected as $key => $categorie) {
                array_push($categorie_array, $categorie['id']);
            }
        }
        
        $IS_EXISTS_START_DATE = Discount::where('id', '<>', $id)
            ->ValidateDiscount($request, $product_array, $categorie_array)
            ->whereBetween('start_date', [$request->start_date, $request->end_date])
            ->first();
        $IS_EXISTS_END_DATE = Discount::where('id', '<>', $id)
            ->ValidateDiscount($request, $product_array, $categorie_array)
            ->whereBetween('end_date', [$request->start_date, $request->end_date])
            ->first();

        if ($IS_EXISTS_START_DATE || $IS_EXISTS_END_DATE) {
            return response()->json([
                'message' => 403,
                'message_error' => 'No se puede registrar descuento.',
            ]);
        }

        $discount = Discount::findOrFail($id);
        $discount->update($request->all());

        if($request->type == 1){
            $discount->discountProducts()->delete();    //elimina id y genera nuevo
            foreach ($product_array as $key => $product_id) {
                DiscountProduct::create([
                    'discount_id' => $discount->id,
                    'product_id' => $product_id,
                ]);
            }
        }

        if($request->type == 2){
            $discount->discountCategories()->delete();  //elimina id y genera nuevo
            foreach ($categorie_array as $key => $categorie_id) {
                DiscountCategorie::create([
                    'discount_id' => $discount->id,
                    'categorie_id' => $categorie_id,
                ]);
            }
        }
        
        return response()->json([
            'message' => 200,
            'descuento' => $discount,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

        return response()->json([
            'message' => 200,
        ]);
    }
}
