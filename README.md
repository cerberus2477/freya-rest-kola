# GameManagementApp

***11.14 játéknyilvántartás feladat - Készítsen egy webes vagy asztali alkalmazást, amely egy játékokhoz tartozó nyilvántartást vezet!***

  
## Futtatás lépései:
1. Töltsd le a projektet. 
 <a href= "https://github.com/cerberus2477/GameManagamentApp/archive/refs/heads/master.zip"><img src="http://img.shields.io/badge/Download_ZIP_green?style=for-the-badge" alt="Download ZIP"></a>
    - Csomagold ki a fájlt a `C:\xampp\htdocs\` mappába.
2. XAMPP indítása (Apache, MySQL)
3. Importáld a *`GameManagamentApp_dump.sql`* fájlt a Phpmyadmin felületén (`localhost/phpmyadmin`)
4. Futtasd a Laravel működéséhez szükséges parancsokat a projekt mappájában.
```cmd
cd GameManagementApp
```
```cmd
composer install
php artisan migrate
php artisan serve
```

Magyarázat parancsonként:
- a célmappába navigálunk
- a projekthez szükséges függőségek telepítése
- adatbázis táblák létrehozása a laravelen belül
- szerver indítása

6. A kezelőfelület megnyitása a `http://127.0.0.1:8000/` címen
7. Enjoy :)



## Freya-jegyzet
gamemanagementappból kezdtem

doing migrations and models currently

## TODO
- -unique legyen a dbben az user email, name
- this
```php
$table->timestamp('email_verified_at')->nullable();
``` 
van most a usernél, dbben lehet kéne (meg így verificitaon tbh)

<hr>

- create db (lehet e meglévő migrationokból, meg van e minden vagy kell az sql?)
- run seeders
```cmd
php artisan db:seed
```
- register Seeders in DatabaseSeeder The DatabaseSeeder file (located in database/seeders/DatabaseSeeder.php) is the entry point for running seeders. You need to call your seeders here.

```php
 public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PlantSeeder::class,
            // Add other seeders here
        ]);
    }
```

```cmd
PS C:\xampp\htdocs\TUNDE-Ne_Torold\kola-freya-rest\GameManagementApp> php artisan migrate

   WARN  The database 'freyas_garden' does not exist on the 'mysql' connection.

  Would you like to create it? (yes/no) [yes]
❯ 

   INFO  Preparing database.  

  Creating migration table ......................................................................................................... 15.26ms DONE

   INFO  Running migrations.  

  0001_01_01_000001_create_cache_table ............................................................................................. 29.05ms DONE
  0001_01_01_000002_create_jobs_table .............................................................................................. 68.90ms DONE
  2024_11_19_145057_run_sql_dump .................................................................................................... 0.14ms DONE  
  2024_12_02_125306_create_articles_table .......................................................................................... 26.34ms FAIL

   Illuminate\Database\QueryException 

  SQLSTATE[HY000]: General error: 1005 Can't create table `freyas_garden`.`articles` (errno: 150 "Foreign key constraint is incorrectly formed") (Connection: mysql, SQL: alter table `articles` add constraint `articles_plant_id_foreign` foreign key (`plant_id`) references `plants` (`id`) on delete set null)

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:825
    821▕                     $this->getName(), $query, $this->prepareBindings($bindings), $e
    822▕                 );
    823▕             }
    824▕
  ➜ 825▕             throw new QueryException(
    826▕                 $this->getName(), $query, $this->prepareBindings($bindings), $e
    827▕             );
    828▕         }
    829▕     }

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:571
      PDOException::("SQLSTATE[HY000]: General error: 1005 Can't create table `freyas_garden`.`articles` (errno: 150 "Foreign key constraint is incorrectly formed")")

  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:571
      PDOStatement::execute()

PS C:\xampp\htdocs\TUNDE-Ne_Torold\kola-freya-rest\GameManagementApp> 

```
ez a legutobbi error.


<hr>
<hr>

# Innentől gamemanagement dolgok


## Saját jegyzetem / micsináltam
Igyekeztem most gyorsabban / optimálisan megoldani a dolgot :3

### Stuff I learned (some of it)
	- `php artisan make:model Player -mcr` - controllert is csinál + migration
	- modellben fillable fields
### Step 1: Set Up Your Laravel Project

#### Install Laravel and start the server:
    
    `composer create-project --prefer-dist laravel/laravel GameDBAdmin`
    `php artisan serve`

#### Update your `.env` file to configure the database settings for `GameDB`:
`DB_DATABASE=GameDB DB_USERNAME=your_db_username DB_PASSWORD=your_db_password`

### Step 1: Create the `layout.blade.php` File and css

In `resources/views/layout.blade.php`, create a main layout file with a navigation bar, main content area, and footer:

```html
<!DOCTYPE html> <html lang="en"> <head>     <meta charset="UTF-8">     <meta name="viewport" content="width=device-width, initial-scale=1.0">     <title>GameDB Admin</title>     <link rel="stylesheet" href="{{ asset('css/app.css') }}"> </head> <body>     <nav>         <ul>             <li><a href="{{ route('players.index') }}">Players</a></li>             <li><a href="#">Games</a></li>             <li><a href="#">Player Games</a></li>         </ul>     </nav>      <main>         @yield('content')     </main>      <footer>         <p>&copy; 2024 GameDB Admin</p>     </footer> </body> </html>
```

***+ home.blade.php létrehozása
***css goes in public/css/app.css***

#### Laraveles (session, user, stb) táblák létrehozása
`php artisan migrate`

### Step 2: Create the Player Model, Controller

#### 1. Create a Model and Controller for `Player`:
    
    `php artisan make:model Player
    `php artisan make:controller Player`
    

    
**Define Fillable Fields in the Player Model:**
    
    app/Models/Player.php

```php
protected $fillable = [     'username', 'password', 'email', 'joinDate', 'age', 'occupation', 'gender', 'city' ]
```

#### 2. Fill in the model
- `$fillable` lista kell a mezők neveivel
- timestamp nem kell

#### 3. Fill in the controller
- vagy a http/requestet használjuk a create és a store metódusnál, vagy saját requestet csinálunk és ott ***validálunk*** (ez van itt)

##### Validálás
`php artisan make:request PlayerRequest`
  
```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayerRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Set to true to allow this request
    }

    public function rules()
    {
        return [
            // Define validation rules here, for example:
            'name' => 'required|string|max:255',
            'team' => 'required|string|max:255',
            'position' => 'required|string|max:50',
        ];
    }
}

```

##### Controller
```php
<?php
namespace App\Http\Controllers;
  
use App\Models\Player;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;


class PlayerController extends Controller

{
    public function index()
    {
        $players = Player::all();
        return view('players.index', compact('players'));
    }
  

    public function create()
    {
        return view('players.create');
    }


    public function store(StorePlayerRequest $request)
    {
        Player::create($request->validated());
        return redirect()->route('players.index');
    }


    public function edit(Player $player)
    {
        return view('players.edit', compact('player'));
    }


    public function update(UpdatePlayerRequest $request, Player $player)
    {
        $player->update($request->validated());
        return redirect()->route('players.index');
    }


    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index');
    }
}
```



### Step 3: Set Up Routes

In `routes/web.php`, define routes for `PlayerController`:

```php
`use App\Http\Controllers\PlayerController;  Route::resource('players', PlayerController::class);`
```


### Step 4: Create Views for the Player CRUD Interface

#### Index View
(`resources/views/players/index.blade.php`):

```php
@extends('layout')

  

@section('content')

<div class="title-add">

    <h1>Players</h1>

    <a href="{{ route('players.create') }}" class="btn btn-primary">Új rekord hozzáadása</a>

</div>

<table>

    <thead>

        <tr>

            <th>ID</th>

            <th>Username</th>

            <th>Email</th>

            <th>Join Date</th>

            <th>Age</th>

            <th>Occupation</th>

            <th>Gender</th>

            <th>City</th>

            <th>Actions</th>

        </tr>

    </thead>

    <tbody>

        @foreach ($players as $player)

            <tr>

                <td>{{ $player->playerID }}</td>

                <td>{{ $player->username }}</td>

                <td>{{ $player->email }}</td>

                <td>{{ $player->joinDate }}</td>

                <td>{{ $player->age }}</td>

                <td>{{ $player->occupation }}</td>

                <td>{{ $player->gender }}</td>

                <td>{{ $player->city }}</td>

                <td class="actions">

                    <a href="{{ route('players.edit', $player->playerID) }}" class="btn btn-warning">Edit</a>

                    <form action="{{ route('players.destroy', $player->playerID) }}" method="POST">

                        @csrf

                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">Delete</button>

                    </form>

                </td>

            </tr>

        @endforeach

    </tbody>

</table>

@endsection
```

    
    
#### Create and Edit Views 
(`resources/views/players/create.blade.php` and `resources/views/players/edit.blade.php`):
	
***lehet hogy lehetne csak egyszer leírva is, de most ugyanaz a két fájl, benne a logika eldönti hogy mi fusson***

```php
@extends('layout')

@section('content')
    <h1>{{ isset($player) ? 'Edit Player' : 'Add New Player' }}</h1>

    <form action="{{ isset($player) ? route('players.update', $player->playerID) : route('players.store') }}" method="POST">
        @csrf
        @if (isset($player))
            @method('PUT')
        @endif

        <label>Username:</label>
        <input type="text" name="username" value="{{ $player->username ?? old('username') }}" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="{{ $player->email ?? old('email') }}" required><br>

        <label>Join Date:</label>
        <input type="date" name="joinDate" value="{{ $player->joinDate ?? old('joinDate') }}"><br>

        <label>Age:</label>
        <input type="number" name="age" value="{{ $player->age ?? old('age') }}"><br>

        <label>Occupation:</label>
        <input type="text" name="occupation" value="{{ $player->occupation ?? old('occupation') }}"><br>

        <label>Gender:</label>
        <input type="text" name="gender" value="{{ $player->gender ?? old('gender') }}"><br>

        <label>City:</label>
        <input type="text" name="city" value="{{ $player->city ?? old('city') }}"><br>

        <button type="submit">{{ isset($player) ? 'Update' : 'Save' }}</button>
    </form>
@endsection



### Step 6: Implement Controller Methods

In `PlayerController`, implement CRUD methods to handle data from the views:

php

Kód másolása

`public function index() {     $players = Player::all();     return view('players.index', compact('players')); }  public function create() {     return view('players.create'); }  public function store(Request $request) {     Player::create($request->all());     return redirect()->route('players.index'); }  public function edit(Player $player) {     return view('players.edit', compact('player')); }  public function update(Request $request, Player $player) {     $player->update($request->all());     return redirect()->route('players.index'); }  public function destroy(Player $player) {     $player->delete();     return redirect()->route('players.index'); }`





```



## Továbbfejlesztési lehetőségek
- show mindenhová - playergames táblázatból lehetne link a playerid és gameid, és odavisz
    - show nem playerid-t írna pl hanem a nevét a játéknak
- kereső
- filter
- egyszerre csak x rekordot töltsön be
- bejelentkezés
	`composer require laravel/ui `
	`php artisan ui `
	`vue --auth`
	`npm install && npm run dev`

- reszponzivitás

- az adatbázist egyből betölteni


## Hibák
-  games oldalon nem jelenik meg semmi valamiért ( van view, van controller)
- mentésnél nem adja hozzá az új rekordot

## TODO
- playergames egyáltalán nincs meg
	- itt majd az edit és add más lesz, legördülő menüből kell kiválasztani a playert és a gamet
