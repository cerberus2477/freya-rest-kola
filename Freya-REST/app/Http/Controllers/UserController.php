<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\StorageHelper;

class UserController extends BaseController
{
    public function login(UserRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
    
        $user = User::where('email', $email)->first();
    
        if (!$user || !Hash::check($password, $user->password)) {
            return $this->jsonResponse(
                401,
                'Invalid credentials',
            );
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
        return $this->jsonResponse(
            200,
            "Login successful",
            ['user' => $user,
            'token' => $token]);
    }
    
    public function register(UserRequest $request)
    {

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'city' => null,
            'birthdate' => null,
            'password' => Hash::make($request->password),
            'description' => null,
            'picture' => StorageHelper::getPlaceholderImage(),
        ]);

        // Create a token for the newly registered user
        $token = $user->createToken('access', ['user'])->plainTextToken;

        return $this->jsonResponse(201,
            'User registered successfully',
            ['user' => $user,
            'token' => $token]
        );
    }

    /**
     * send password reset email
     */
    public function sendResetLinkEmail(UserRequest $request)
    {
        // Send the password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Return a JSON response based on the status
        return $status === Password::RESET_LINK_SENT
            ? $this->jsonResponse(200, "Email küldése sikeres")
            : $this->jsonResponse(400, "Email küldés sikertelen: $status");
    }

    /**
     * Resets the users password to the password geve with a password reset token
     */
    public function passwordReset(UserRequest $request){
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password), // Hash the password
                ])->save();
                $user->tokens()->delete();
            }
        );

        // Return a JSON response based on the status
        return $status === Password::PASSWORD_RESET
            ? $this->jsonResponse(200, "Password reset succesuffly")
            : $this->jsonResponse(400, "Password reset failed");

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->jsonResponse(
            200,
            "Data retrived succesfully",
            User::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(UserRequest $request, string $username)
    {
        $user = User::where('username', $username)->first();

        if($user == $request->user()){
            return $this->jsonResponse(200, 'Own user retrived succesfully', $user);
        }elseif($user){
            return $this->jsonResponse(200, 'User retrived succesfully', $user);
        } 
        else{
            return $this->jsonResponse(404, 'User not foound');
        }
    }


    public function showMyPlants(UserRequest $request)
{
    $user = $request->user();

    $userPlants = $user->userPlants()->with('plant.type', 'stage')->get();

    $response = [
        'user' => [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'city' => $user->city,
            'birthdate' => $user->birthdate,
            'picture' => $user->picture,
            'description' => $user->description,
            'role_id' => $user->role_id,
            'role_name' => $user->role->name,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ],
        'plants' => $userPlants->map(function ($userPlant) {
            return [
                'id' => $userPlant->id,
                'plant' => [
                    'id' => $userPlant->plant->id,
                    'name' => $userPlant->plant->name,
                    'latin_name' => $userPlant->plant->latin_name,
                    'type' => $userPlant->plant->type->name,
                ],
                'stage' => [
                    'id' => $userPlant->stage->id,
                    'name' => $userPlant->stage->name,
                ],
                'count' => $userPlant->count,
            ];
        })
    ];

    return $this->jsonResponse(200, 'Successfully returned user\'s plants and data', $response);
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

        return $this->jsonResponse(200, 'User updated successufully',$user);
    }

    /**
     * update specified users role
     */
    public function roleUpdate(UserRequest $request, string $username){
        try{
            $user = User::where('username', $username)->firstOrFail();
            $user->role_id = $request->input('role_id');
            return $this->jsonResponse(200, 'User role updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->jsonResponse(404, 'User not found');
        }
    }

    public function destroy(UserRequest $request, ?string $username = null)
    {
        try {
            if($username){
                $user = User::where('username', $username)->firstOrFail();
            }
            elseif($request->user()){
                $user = $request->user();
            }
            $user->delete();

            return $this->jsonResponse(200, 'user deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->jsonResponse(404, 'User not found');
        }
    }

    public function restore(string $username)
    {
        try {
            $user = User::onlyTrashed()->where('username', $username)->firstOrFail();

            $user->restore();

            return $this->jsonResponse(200, 'User restored successfully');
        } catch (ModelNotFoundException $e) {
            return $this->jsonResponse(404, 'User not found');
        }
    }
}

//apidoc

/**
 * @api {post} /login Log in
 * @apiName LoginUser
 * @apiGroup Authentication
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
 *             "role_id": "2"
 *         },
 *         "token": "2|abcdef1234567890"
 *     }
 *
 * @apiError {String} message Error message.
 * @apiErrorExample {json} Error-Response:
 *     HTTP/1.1 401 Unauthorized
 *     {
 *         "message": "Invalid credentials"
 *     }
 */

/**
 * @api {post} /register Register a new user
 * @apiName RegisterUser
 * @apiGroup Authentication
 * @apiDescription Registers a new user and logs them in by returning an access token.
 *
 * @apiBody {String} username User's desired username.
 * @apiBody {String} email User's email address.
 * @apiBody {String} password User's password.
 * @apiBody {String} password_confirmation Confirmation of the user's password.
 *
 * @apiSuccess {String} message Success message.
 * @apiSuccess {Object} user Registered user details.
 * @apiSuccess {String} user.id User's unique ID.
 * @apiSuccess {String} user.username User's username.
 * @apiSuccess {String} user.email User's email address.
 * @apiSuccess {String} token Access token for the newly registered user.
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 201 Created
 *     {
 *         "message": "User registered successfully",
 *         "user": {
 *             "id": 1,
 *             "username": "johndoe",
 *             "email": "johndoe@example.com"
 *         },
 *         "token": "1|abcdef1234567890"
 *     }
 *
 * @apiError {String} message Error message.
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

/**
 * @api {post} /forgot-password Send Password Reset Email
 * @apiName ForgotPassword
 * @apiGroup Authentication
 * @apiDescription Sends a password reset email to the user.
 *
 * @apiBody {String} email User's email address.
 *
 * @apiSuccess {String} message Success message.
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "message": "Email sent successfully"
 *     }
 *
 * @apiError {String} message Error message.
 * @apiErrorExample {json} Error-Response:
 *     HTTP/1.1 400 Bad Request
 *     {
 *         "message": "Email sending failed"
 *     }
 */

/**
 * @api {post} /reset-password Reset Password
 * @apiName ResetPassword
 * @apiGroup Authentication
 * @apiDescription Resets the user's password using a reset token.
 *
 * @apiBody {String} token Password reset token.
 * @apiBody {String} email User's email address.
 * @apiBody {String} password New password.
 * @apiBody {String} password_confirmation Confirmation of the new password.
 *
 * @apiSuccess {String} message Success message.
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "message": "Password reset successfully"
 *     }
 *
 * @apiError {String} message Error message.
 * @apiErrorExample {json} Error-Response:
 *     HTTP/1.1 400 Bad Request
 *     {
 *         "message": "Password reset failed"
 *     }
 */

/**
 * @api {get} /profile Get User Profile
 * @apiName GetUserProfile
 * @apiGroup User
 * @apiDescription Retrieve the authenticated user's profile and their plants.
 *
 * @apiSuccess {Object} user User details.
 * @apiSuccess {Object[]} plants List of user's plants.
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
 *             "role_id": "2"
 *         },
 *         "plants": [
 *             {
 *                 "id": 1,
 *                 "plant": {
 *                     "id": 1,
 *                     "name": "Rose",
 *                     "latin_name": "Rosa",
 *                     "type": "Flower"
 *                 },
 *                 "stage": {
 *                     "id": 1,
 *                     "name": "Seedling"
 *                 },
 *                 "count": 5
 *             }
 *         ]
 *     }
 */

/**
 * @api {patch} /profile Update User Profile
 * @apiName UpdateUserProfile
 * @apiGroup User
 * @apiDescription Update the authenticated user's profile details.
 *
 * @apiBody {String} [username] User's new username.
 * @apiBody {String} [email] User's new email address.
 * @apiBody {String} [city] User's city.
 * @apiBody {Date} [birthdate] User's birthdate.
 * @apiBody {String} [description] User's description.
 * @apiBody {String} [picture] User's profile picture URL.
 *
 * @apiSuccess {Object} user Updated user details.
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "message": "User updated successfully",
 *         "user": {
 *             "id": 1,
 *             "username": "newusername",
 *             "email": "newemail@example.com",
 *             "city": "New York",
 *             "birthdate": "1990-01-01",
 *             "description": "Updated description",
 *             "picture": "http://example.com/profile.jpg"
 *         }
 *     }
 */

/**
 * @api {delete} /profile Delete User Profile
 * @apiName DeleteUserProfile
 * @apiGroup User
 * @apiDescription Delete the authenticated user's profile.
 *
 * @apiSuccess {String} message Success message.
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "message": "User deleted successfully"
 *     }
 */

/**
 * @api {patch} /users/:username/role Update User Role
 * @apiName UpdateUserRole
 * @apiGroup Admin
 * @apiDescription Update the role of a specific user.
 *
 * @apiParam {String} username The username of the user whose role is being updated.
 * @apiBody {Integer} role_id The new role ID.
 *
 * @apiSuccess {String} message Success message.
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "message": "User role updated successfully"
 *     }
 */