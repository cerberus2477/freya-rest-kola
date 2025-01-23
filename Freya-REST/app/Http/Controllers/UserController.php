<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Logs in with email and password
     * returns token.
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where ('email', $email)->first();

        if(!user || !Hash::check($password, $password ? $user -> password: '')){
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        //revoke old tokens
        $user->tokens()->delete();

        $user ->token = $user->createToken('acces')->plainTextToken;

    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(User::findOrFail($id));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {

        $user = User::create($request->validated());
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->validated());
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204); // 204 No Content response to indicate deletion
    }
}

