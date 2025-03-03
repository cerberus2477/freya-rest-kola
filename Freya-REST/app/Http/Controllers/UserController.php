<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
 * @api {post} /login Log in with email and password
 * @apiName LoginUser
 * @apiGroup Authentication
 *
 * @apiDescription Logs in a user by validating their email and password, and returns an access token with specific abilities based on their role.
 *
 * @apiBody {String} email User's email address.
 * @apiBody {String} password User's password.
 *
 * @apiSuccess {Object} user Logged-in user details.
 * @apiSuccess {String} user.id User's unique ID.
 * @apiSuccess {String} user.username User's username.
 * @apiSuccess {String} user.email User's email address.
 * @apiSuccess {String} user.city User's city (nullable).
 * @apiSuccess {String} user.birthdate User's birthdate (nullable).
 * @apiSuccess {String} user.role_id User's role ID.
 * @apiSuccess {String} token Access token for the logged-in user.
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "user": {
 *             "id": 1,
 *             "username": "johndoe",
 *             "email": "johndoe@example.com",
 *             "city": "New York",
 *             "birthdate": "1990-01-01",
 *             "role_id": "2",
 *             "created_at": "2023-10-01T12:00:00.000000Z",
 *             "updated_at": "2023-10-01T12:00:00.000000Z"
 *         },
 *         "token": "2|abcdef1234567890"
 *     }
 *
 * @apiError {String} message Error message.
 *
 * @apiErrorExample {json} Error-Response:
 *     HTTP/1.1 401 Unauthorized
 *     {
 *         "message": "Invalid credentials"
 *     }
 */
    public function login(UserRequest $request)
    {
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
    * @api {post} /register Register a new user
    * @apiName RegisterUser
    * @apiGroup Authentication
    *
    * @apiDescription Registers a new user and logs them in by returning an access token.
    *
    * @apiBody {String} username User's desired username (max: 255 characters).
    * @apiBody {String} email User's email address (must be unique).
    * @apiBody {String} password User's password (min: 6 characters).
    * @apiBody {String} password_confirmation Confirmation of the user's password.
    *
    * @apiSuccess {String} message Success message.
    * @apiSuccess {Object} user Registered user details.
    * @apiSuccess {String} user.id User's unique ID.
    * @apiSuccess {String} user.username User's username.
    * @apiSuccess {String} user.email User's email address.
    * @apiSuccess {String} user.city User's city (nullable).
    * @apiSuccess {String} user.birthdate User's birthdate (nullable).
    * @apiSuccess {String} token Access token for the newly registered user.
    *
    * @apiSuccessExample {json} Success-Response:
    *     HTTP/1.1 201 Created
    *     {
    *         "message": "User registered successfully",
    *         "user": {
    *             "id": 1,
    *             "username": "johndoe",
    *             "email": "johndoe@example.com",
    *             "city": null,
    *             "birthdate": null,
    *             "role_id": 3
    *         },
    *         "token": "1|abcdef1234567890"
    *     }
    *
    * @apiError {String} message Error message.
    * @apiError {Object} errors Validation errors.
    *
    * @apiErrorExample {json} Error-Response:
    *     HTTP/1.1 422 Unprocessable Entity
    *     {
    *         "message": "Validation failed",
    *         "errors": {
    *             "username": ["The username field is required."],
    *             "email": ["The email field is required."],
    *             "password": ["The password field is required."]
    *         }
    *     }
    */
    public function register(UserRequest $request)
    {

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'city' => null,
            'birthdate' => null,
            'password' => Hash::make($request->password),
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
    public function show(string $username)
    {
        $user = User::where('username',$username)->first();

        if($user){
            return response()->json($user);
        } else{
            return response()->json(['message' => 'Nem talált felhasználó'],404);
        }
    }

    public function showSelf(UserRequest $request){//TODO not at all finished
        $user = $request->user();
        $response = DB::table()
            ->Join('user_plants', 'users.id', '=', 'user_plants.user_id');



        return response()->json($user);
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
     * validation dies if you send in the same username/email as you have
     */
    public function update(UserRequest $request, ?string $username = null)
    {
        if ($username) {
            $user = User::where('username', $username)->firstOrFail();
        } else {
            $user = $request->user();
        }

        $user->update($request->validated());

        return response()->json($user, 200);
    
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

