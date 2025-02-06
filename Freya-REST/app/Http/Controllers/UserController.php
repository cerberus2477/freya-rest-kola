<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Logs in with email and password
     * 
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        $email = $request->input('email');
        $password = $request->input('password');
    
        $user = User::where('email', $email)->first();
    
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
    
        // Revoke old tokens
        $user->tokens()->delete();
    
        $abilities = [];
        switch($user->access_level){
            case '1':
                $abilities = ['user', 'stats', 'admin'];
               break;
            case '2':
                $abilities = ['user', 'stats'];
                break;
            case '3':
                $abilities = ['user'];
                break;
            default:
                $abilities = ['read'];
                break;
        }

        // Create new token, with abilities
        $token = $user->createToken('access', $abilities)->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
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
    public function store(Request $request)
    {

        $user = User::create($request->validated());
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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

