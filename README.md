# Freya's REST

***Az éves projektünkhöz (Freya's Garden) készül a REST api Laravelben, bár graphql apit szeretnénk. Ez ilyen backup addig, meg valamit le kell adni. ***

  
## Futtatás lépései:
1. Töltsd le a projektet. 
 <a href= "https://github.com/cerberus2477/GameManagamentApp/archive/refs/heads/master.zip"><img src="http://img.shields.io/badge/Download_ZIP_green?style=for-the-badge" alt="Download ZIP"></a>
    - Csomagold ki a fájlt a `C:\xampp\htdocs\` mappába.
2. XAMPP indítása (Apache, MySQL)
3. *(Importáld a *`GameManagamentApp_dump.sql`* fájlt a Phpmyadmin felületén (`localhost/phpmyadmin`))*
4. Futtasd a Laravel működéséhez szükséges parancsokat a projekt mappájában.
```cmd
composer install
php artisan migrate
php artisan serve --port 8069
```

Magyarázat parancsonként:
- a célmappába navigálunk
- a projekthez szükséges függőségek telepítése
- adatbázis táblák létrehozása a laravelen belül
- szerver indítása a megadott porton. a post megadása akkor fontos, ha a klienssel együtt szeretnénk használni, hiszen itt próbál majd csatlakozni a kliens.

6. A kezelőfelület megnyitása a `http://127.0.0.1:8069/` címen
7. Enjoy :)



## Freya-jegyzet
gamemanagementappból kezdtem

## TODO
- -unique legyen a dbben az user email, name
- this
```php
$table->timestamp('email_verified_at')->nullable();
``` 
van most a usernél, dbben lehet kéne (meg így verificitaon tbh)

- validation külön fájlba

<hr>

- create db (lehet e meglévő migrationokból, meg van e minden vagy kell az sql?)
- run seeders
```bash
php artisan db:seed
```

```bash
php artisan migrate:refresh --seed
```

más mód: külön lehet defineolni hogy milyen adatokat adunk vissza:
        // Create the new plant
        $plant = Plant::create($validated);

        // Return a response with the created plant data
        return new PlantResource($plant); // Optional: Using a resource for transformation


- hogy kell azt megcsinálni hogy a json az adatokon kívűl jó kódot is visszadjon, meg ha még kell akkor mást is? 

## Egyéb random notes
- egyelőre a /api/plants visszaadja a created at meg az updated at mezőket is
- usernél null az értéke a plsuz mezőknek, mert a seederben így van írva (db:insert vs create)
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
