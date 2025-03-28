# Freya's REST

***Az éves projektünkhöz (Freya's Garden) készül a REST api Laravelben***

  
## Futtatás lépései:
1. Töltsd le a projektet. 
 <a href= "https://github.com/cerberus2477/freya-rest-kola/archive/refs/heads/master.zip"><img src="http://img.shields.io/badge/Download_ZIP_green?style=for-the-badge" alt="Download ZIP"></a>
    - Csomagold ki a fájlt a `C:\xampp\htdocs\` mappába.
2. XAMPP indítása (Apache, MySQL)
    - ki kell kommentelni a php.ini-ben az ;extensions:gd-t ha képeket akarunk tárolni.
3. Futtasd a Laravel működéséhez szükséges parancsokat a projekt mappájában.
```cmd
composer install (composer update)
php artisan migrate:refresh --seed
php artisan serve --port 8069
```
4. Az api megnyitása a `http://127.0.0.1:8069/` címen.  Ajánlott pl. Postman, EchoAPI használata teszteléshez. Enjoy :)



### egyéb parancsok
#### Ha már egyszer leszedted a dolgokat, és újra elindítanád

xampp
```cmd
(git switch master)
git pull origin master
cd .\Freya-REST\
(composer update)
php artisan migrate:refresh --seed
php artisan serve --port 8069
```

(`composer update`)
`php artisan route:list`
#### Tesztek
`php artisan test`
`php artisan make:test ...`
#### dolgok frissítése
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```
#### Undo git add 
The proper way to undo the git add command is to use git restore.
`git restore –staged file.py`

## apidoc
Step 6: Automate API Docs Generation (Optional)
To regenerate the docs every time you update them, add a script in composer.json. - done

```bash
npm install -g apidoc
composer require --dev barryvdh/laravel-ide-helper
(Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass) - suliban ha nincs jogod
apidoc -i app/Http/Controllers -o public/apidoc

```

Magyarázat parancsonként:
- a projekthez szükséges függőségek telepítése
- adatbázis táblák létrehozása a laravelen belül (migration) és feltöltés adatokkal (seedelés)
- szerver indítása a megadott porton.
  	- a port megadása akkor fontos, ha a klienssel együtt szeretnénk használni, hiszen itt próbál majd csatlakozni a kliens.

Potential errors:
- `composer install` eredménye sok sok ilyesmi: `Failed to download psr/log from dist: The zip extension and unzip command are both missing, skipping.`
	- **megoldás**: `C:\xampp\php\php.ini`-ben `extension=zip` legyen `;` nélkül.




## notes
### Articleseeder content md
Ensure `\n` is Rendered Properly in HTML
If you're displaying this content in a web application, use:

```blade
{!! nl2br(e($article->content)) !!}
```
This ensures \n is converted to <br> in HTML.

### külön lehet defineolni hogy milyen adatokat adunk vissza:
 ```php 
    // Create the new plant
    $plant = Plant::create($validated);

    // Return a response with the created plant data
    return new PlantResource($plant); // Optional: Using a resource for transformation
```

