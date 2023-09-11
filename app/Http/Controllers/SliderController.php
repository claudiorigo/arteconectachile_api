<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
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
        $sliders = Slider::orderBy('id', 'DESC')->get();
        return response()->json([
            'sliders' => $sliders,
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
            $path = Storage::putFile('sliders', $request->file('image_file'));           
            $request->request->add(['imagen' => $path]);
        }

        $slider = Slider::create($request->all());
        return response()->json([
            'slider' => $slider,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);
        if ($request->hasFile('image_file')) {
            if ($slider->imagen) {
                Storage::delete($slider->imagen);
            }
            $path = Storage::putFile('sliders', $request->file('image_file'));
            $request->request->add(['imagen' => $path]);
        }
        $slider->update($request->all());
        return response()->json([
            'slider' => $slider,
            'message' => 201
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->delete();
        return response()->json([
            'message' => 200
        ]);
    }
}
