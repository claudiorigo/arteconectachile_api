<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategorieController extends Controller
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
        $categories = Categorie::where('name', 'like', '%' . $search .'%')->orderBy('id', 'DESC')->get();
        return response()->json([
            'categorias' => $categories,
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
        if ($request->hasFile('image_file')) {
            $path = Storage::putFile('categorias', $request->file('image_file'));           
            $request->request->add(['imagen' => $path]);
        }

        $categorie = Categorie::create($request->all());
        return response()->json([
            'categorie' => $categorie,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorie $categorie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categorie $categorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);
        if ($request->hasFile('image_file')) {
            if ($categorie->imagen) {
                Storage::delete($categorie->imagen);
            }
            $path = Storage::putFile('categorias', $request->file('image_file'));
            $request->request->add(['imagen' => $path]);
        }
        $categorie->update($request->all());
        return response()->json([
            'categorie' => $categorie,
            'message' => 201
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->delete();
        return response()->json(['message' => 200]);
    }
}
