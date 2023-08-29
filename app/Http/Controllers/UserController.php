<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
        $state = $request->get('state');
        $search = $request->get('search');

        $users = User::filterAdvance($state, $search)->where('type_user', 2)->orderBy('id', 'DESC')->paginate(20);
        return response()->json([
            'total' => $users->total(),
            'users' => $users,
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
        $user = User::where('email', $request->email)->first();
        if($user){
            return response()->json(['message' => 400]);
        }else{
            $user = User::create($request->all());
            return response()->json(['message' => 200, 'user' => $user]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::where('email', $request->email)->where('id','<>', $id)->first();
        if($user){
            return response()->json(['message' => 400]);
        }else{
            $user = User::findOrFail($id);
            $user->update($request->all());
            return response()->json(['message' => 200, 'user' => $user]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 200]);
    }
}
