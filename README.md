# Freya's REST

***Az éves projektünkhöz (Freya's Garden) készül a REST api Laravelben***

  
## Futtatás lépései:
1. Töltsd le a projektet. 
 <a href= "https://github.com/cerberus2477/freya-rest-kola/archive/refs/heads/master.zip"><img src="http://img.shields.io/badge/Download_ZIP_green?style=for-the-badge" alt="Download ZIP"></a>
    - Csomagold ki a fájlt a `C:\xampp\htdocs\` mappába.
2. XAMPP indítása (Apache, MySQL)
3. Futtasd a Laravel működéséhez szükséges parancsokat a projekt mappájában.
```cmd
composer install
php artisan migrate:refresh --seed
php artisan serve --port 8069
```
4. Az api megnyitása a `http://127.0.0.1:8069/` címen.  Ajánlott pl. Postman használata. Enjoy :)

(`composer update`)

Magyarázat parancsonként:
- a projekthez szükséges függőségek telepítése
- adatbázis táblák létrehozása a laravelen belül (migration) és feltöltés adatokkal (seedelés)
- szerver indítása a megadott porton.
  	- a port megadása akkor fontos, ha a klienssel együtt szeretnénk használni, hiszen itt próbál majd csatlakozni a kliens.

Potential errors:
- `composer install` eredménye sok sok ilyesmi: `Failed to download psr/log from dist: The zip extension and unzip command are both missing, skipping.`
	- **megoldás**: `C:\xampp\php\php.ini`-ben `extension=zip` legyen `;` nélkül.




## TODO
- i have a different articlecontroller.php class. i want the same thing to happen, but with articles now. /api/articles (searcheable) and /api/articles/{title}


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
- 
<hr>
<hr>


# Innentől random dolgok amit eddig tanultam más projecteknél és kellhet


### Validálás
a http/requestet használjuk a create és a store metódusnál, vagy saját requestet csinálunk és ott ***validálunk***

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

### bejelentkezés
	`composer require laravel/ui `
	`php artisan ui `
	`vue --auth`
	`npm install && npm run dev`

