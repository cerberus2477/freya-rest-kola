# GameManagementApp
***Készítsen egy webes vagy asztali alkalmazást, amely egy játékokhoz tartozó nyilvántartást vezet!***

## Futtatás lépései:
1. XAMPP indítása
2. *GameManagamentApp_dump.sql* file importálása phpmyadmin felületen
3. Szerver indítása
   `php artisan serve`
4. Enjoy :)

## Laravellel kapcsolatos megjegyzések
- Migrationra és Seederekre nem volt szükségem, ahogy Database Schemára sem, mert a *GameManagamentApp_dump.sql* fájlban vannak a
    - Create DB
    - Create Table
    - és Insert parancsok.
- Igyekeztem most gyorsabban / optimálisan megoldani a dolgot :3