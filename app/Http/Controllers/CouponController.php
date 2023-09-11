<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CouponController extends Controller
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
        $coupons = Coupon::where('code', 'like', '%'. $request->search .'%')->orderBy('id', 'DESC')->get();        
        return response()->json([
            'message' => 200,
            'cupones' => $coupons,            
        ]);
    }

    public function categories_products_all(){        
        $categories = Categorie::orderBy('id', 'DESC')->get();
        $products = Product::where('state', 2)->orderBy('id', 'DESC')->get();
        return response()->json([
            'message' => 200,            
            'categorias' => $categories,
            'productos' => $products,
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
        $is_valid = Coupon::where('code', $request->code)->first();
        if ($is_valid) {
            return response()->json([
                'message' => 403,
                'message_error' => 'El cupón ingresado ya existe.',
            ]);
        }

        if($request->type_coupon == 1){
            $products = [];
            foreach ($request->products_selected as $key => $product) {
                array_push($products, $product['id']);
            }
            $request->request->add(['products' => implode(',', $products)]);
        }

        if($request->type_coupon == 2){
            $categories = [];
            foreach ($request->categories_selected as $key => $categorie) {
                array_push($categories, $categorie['id']);
            }
            $request->request->add(['categories' => implode(',', $categories)]);
        }

        Coupon::create($request->all());
        return response()->json([
            'message' => 200,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $coupon = Coupon::findOrFail($id);
        return response()->json([
            'message' => 200,
            'cupon' => $coupon,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $is_valid = Coupon::where('id', '<>', $id)->where('code', $request->code)->first();
        if ($is_valid) {
            return response()->json([
                'message' => 403,
                'message_error' => 'El cupón ingresado ya existe.',
            ]);
        }

        if($request->type_coupon == 1){
            $products = [];
            foreach ($request->products_selected as $key => $product) {
                array_push($products, $product['id']);
            }
            $request->request->add(['products' => implode(',', $products)]);
        }

        if($request->type_coupon == 2){
            $categories = [];
            foreach ($request->categories_selected as $key => $categorie) {
                array_push($categories, $categorie['id']);
            }
            $request->request->add(['categories' => implode(',', $categories)]);
        }        

        $coupon = Coupon::findOrFail($id);
        $coupon->update($request->all());
        return response()->json([
            'message' => 200,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return response()->json([
            'message' => 200,
        ]);
    }
}
