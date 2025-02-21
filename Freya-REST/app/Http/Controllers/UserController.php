<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        switch($user->role_id){
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
                $abilities = [];
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
     * Registers a new user - might instantly log them in
     * 
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [//TODO what do we need when registering
            'username'=>['required|string|max255'],
            'birthdate'=>['required'],
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }


        // Create the user
        $user = User::create([//TODO adjust based on the input, and requirements above
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3,
        ]);

        // Create a token for the newly registered user
        $token = $user->createToken('access', ['user'])->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
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

