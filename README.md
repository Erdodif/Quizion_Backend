<p align="center"><img src="public/images/logo.png" width="400"></p>

# Quizion Backend

Quizion egy webböngészős, asztali és mobil kliensre készülő kvízalkalmazás, amellyel a felhasználók kvízek formájában mérhetik össze tudásukat, bővíthetik lexikális ismereteiket, valamint figyelemmel kísérhetik eredményeiket az interneten.
> Az alkalmazás célja, hogy egy, a kvízek köré épült közösség jöhessen létre, valamint, hogy a tanulás és a számonkérés egy új és interaktív formában történhessen. A felhasználóbarát kialakítás és a személyre szabhatóság fontos és elengedhetetlen részét képezi az alkalmazásnak.

## Szerver kiszolgáló telepítése:

- Létrehozni egy üres mappát (pl.: az asztalon).
- Megnyitni a Visual Studio Code-ot és az File->Open Folder gombokkal megnyitni az előbb létrehozott üres mappát.
- A Source Control fülön létrehozni egy git repót (initialize repository).
- Elindítani a XAMPP Apache és MySQL szerverét majd a phpMyAdmin segítségével létrehozni egy quizion nevű utf8mb4_hungarian_ci kódolású adatbázist.
- A ’.env.example’ fájlt lemásolni és a másolatot ’.env’-re átnevezni.
- A ’.env’ fájlban lévő DB_DATABASE értékét átírni quizion-ra.
- Az alábbi parancsokat kell lefuttatni a Visual Studio Code termináljában:
- git clone https://github.com/Erdodif/Quizion_Backend.git
- composer install
- php artisan key:generate 
- php artisan migrate:fresh --seed
- php artisan serve

### A backend helyes telepítésének tesztelése:

- Thunder client-ben: http://127.0.0.1:8000/api/quizzes/all
- Ha visszaadja az összes kvízt JSON formátumban akkor helyesen lett telepítve a backend.
