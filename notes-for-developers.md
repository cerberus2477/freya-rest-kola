


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

