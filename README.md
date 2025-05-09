# Freya's REST

***Az éves projektünkhöz (Freya's Garden) készült REST-API PHP Laravel használatával***

## Szükséges előfeltételek az API futtatásához
*(a teljesség igénye nélkül)*
### Szoftverek:
- XAMPP – Tartalmazza az Apache webszervert, PHP-t (min. 8.1-es verzió) és MySQL-t.
- Composer – PHP függőségek telepítéséhez (Laravel-hez elengedhetetlen).
- Git (opcionális) – A projekt klónozásához.
- Postman / EchoAPI / Insomnia (opcionális) – REST API teszteléshez.

### PHP kiterjesztések (XAMPP php.ini fájlban):
A `php.ini` (alapbeállítás szerint `C:\xampp\php\php.ini`) fájl a következő sorokat komment nélkül kell tartalmazza (; nélkül):
```ini
extension=gd
extension=zip
extension=pdo_mysql
```

Ezek különösen fontosak:
- `gd`: képek kezeléséhez
- `zip`: csomagok letöltéséhez (Composer telepítésnél szükséges)
- `pdo_mysql`: Laravel adatbázis kapcsolat működéséhez
  
## Futtatás lépései:
1. Töltsd le a projektet és helyezd azt át a `C:\xampp\htdocs\` mappán belülre.
 <a href= "https://github.com/cerberus2477/freya-rest-kola/archive/refs/heads/master.zip"><img src="http://img.shields.io/badge/Download_ZIP_green?style=for-the-badge" alt="Download ZIP"></a>

2. XAMPP indítása (Apache, MySQL)

3. Futtasd a Laravel működéséhez szükséges parancsokat a projekt mappájában.

```cmd
composer install
php artisan migrate:refresh --seed
php artisan storage:link
php artisan serve --port 8069
```

Magyarázat parancsonként:
- a projekthez szükséges függőségek telepítése
- adatbázis táblák létrehozása a laravelen belül (migration) és feltöltés adatokkal (seedelés)
- ez a parancs az API-ban tárolt publikusan elérhető képek eléréséhez szükséges.
- szerver indítása a megadott porton.

4. Az API mostmár fut. Enjoy :)
Ha szerenénk közvetlenül tesztelni, ezt a `http://127.0.0.1:8069/` címen tehetjük meg.
Teszteléshez ajánlott a Postman vagy EchoAPI szoftver használata.









