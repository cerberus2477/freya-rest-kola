# Laravel Telepítése és REST API Készítése

## Laravel Telepítése
```bash
composer create-project laravel/laravel laravel-rest-api
cd laravel-rest-api

## .env Fájlban Konfigurációs Beállítások

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_rest_api
DB_USERNAME=root
DB_PASSWORD=
```

## Api hozzáadása
```bash
php artisan install:api
```

## Felhasználó hozzáadása a `DatabaseSeeder`-ben
```php
class DatabaseSeeder extends Seeder
{
    /**
     * 
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => '12345678',
        ]);
    }
}

```

## Seeder futtatása
```bash
php artisan db:seed
```

## Login funckció hozzáadása a Controllerhez
```bash
php artisan make:controller UserController 
```

```php
class UsersController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401); // Unauthorized
        }

        // Revoke old tokens
        $user->tokens()->delete();

        $user->token = $user->createToken('access')->plainTextToken;

        return response()->json([
            'user' => $user,
        ]);
    }
}
```

## Végpont hozzáadása a `api.php` fájlhoz

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

Route::post('/users/login', [UsersController::class, 'login']); 

```

## `app\Http\Models\User.php` adjuk hozzá a ```HasApiTokens``` trait-et

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; 
	/**
 * A User modellben elrejtük azokat a mezőket, amelyek nem akarunk a response-ban megmutatni
 */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'name',
        'email_verified_at',
        'created_at',
        'updated_at'
    ]; 
```

## GET /users Végpont Hozzáadása Controllerben (UserController)

```php
public function index(Request $request)
{
    $users = User::all();
    return response()->json([
        'users' => $users,
    ]);
}
```

## Végpont Hozzáadása az api.php-hoz

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

Route::post('/users/login', [UsersController::class, 'login']);
Route::get('/users', [UsersController::class, 'index'])->middleware('auth:sanctum');
```


---

# Megjegyzések:
1. **Hitelesítés**:
   - A `GET /users` végpont `Sanctum` hitelesítést igényel. Ügyelj arra, hogy a megfelelő middleware be legyen állítva.
   - A hozzáférési tokenek helyes működése érdekében a `Sanctum` konfigurációját ellenőrizd.

2. **Adatbázis és Seeder**:
   - A seederben definiált alapértelmezett felhasználó jelszavának hash-elése fontos. Használj például `bcrypt()` metódust a jelszavak titkosítására.

3. **API Verziózás**:
   - Érdemes lehet verziózott API-t használni, pl. `Route::prefix('api/v1')->group(...)`.

4. **Egyéb Végpontok**:
   - További funkciókhoz, például regisztráció vagy jelszókezelés, érdemes külön metódusokat és végpontokat létrehozni.

5. **Biztonság**:
   - Az API autentikációhoz és biztonsági rétegekhez érdemes a `Laravel Sanctum` teljes dokumentációját tanulmányozni.
