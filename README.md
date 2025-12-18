# Custom PHP Framework

Prosty, lekki framework PHP oparty o architekturę MVC (Model-View-Controller) z file-based routingiem. Stworzony do nauki i szybkiego prototypowania aplikacji webowych.

## 📋 Spis treści

- [Wymagania](#wymagania)
- [Instalacja i uruchomienie](#instalacja-i-uruchomienie)
- [Struktura projektu](#struktura-projektu)
- [Konfiguracja](#konfiguracja)
- [Model](#model)
- [Controller](#controller)
- [Routes (Routing)](#routes-routing)
- [Templates](#templates)
- [Components](#components)
- [Style CSS / LESS](#style-css--less)
- [Migracje SQL](#migracje-sql)

---

## Wymagania

- PHP 8.0 lub nowszy
- SQLite (lub inna baza danych obsługiwana przez PDO)
- Wbudowany serwer PHP lub Apache/Nginx

## Instalacja i uruchomienie

### 1. Sklonuj repozytorium
```bash
git clone <url-repozytorium>
cd custom-php-framework
```

### 2. Skonfiguruj bazę danych
```bash
cp config/config.dist.php config/config.php
```
Następnie edytuj `config/config.php` i dostosuj ustawienia połączenia z bazą danych (prawdopodobnie nie musisz nic zmieniać).

### 4. Uruchom serwer deweloperski
```bash
php -S localhost:3000 -t public
```

### 5. Otwórz aplikację
Przejdź w przeglądarce na adres: [http://localhost:3000](http://localhost:3000)

---

## Struktura projektu

```
custom-php-framework/
├── autoload.php          # Autoloader klas PHP
├── config/               # Pliki konfiguracyjne
│   ├── config.dist.php   # Szablon konfiguracji (commitowany)
│   └── config.php        # Lokalna konfiguracja (NIE commitować!)
├── public/               # Webroot - jedyny folder dostępny publicznie
│   ├── index.php         # Front Controller - punkt wejścia aplikacji
│   └── assets/           # Pliki statyczne (CSS)
├── sql/                  # Migracje bazy danych
├── src/                  # Kod źródłowy aplikacji
│   ├── Controller/       # Kontrolery
│   ├── Model/            # Modele (ActiveRecord)
│   ├── Service/          # Serwisy pomocnicze (Router, Config)
│   ├── Exception/        # Własne wyjątki
│   ├── Routes/           # Definicje ścieżek (file-based routing)
│   └── helpers.php       # Funkcje pomocnicze (render, component)
└── templates/            # Szablony HTML/PHP
    └── components/       # Reużywalne komponenty UI
```

---

## Konfiguracja

Folder `config/` zawiera ustawienia aplikacji:

| Plik | Opis |
|------|------|
| `config.dist.php` | Szablon z domyślnymi ustawieniami - **commitowany do repozytorium** |
| `config.php` | Lokalna konfiguracja - **NIE commitować!** (zawiera dane wrażliwe) |

```php
// Przykład config/config.php
return [
    'db_dsn'  => 'sqlite:' . __DIR__ . '/../data.db',
    'db_user' => null,
    'db_pass' => null,
];
```

Dostęp do konfiguracji w kodzie:
```php
use App\Service\Config;
$dsn = Config::get('db_dsn');
```

---

## Model

Modele znajdują się w `src/Model/` i reprezentują tabele w bazie danych. Framework wykorzystuje wzorzec **ActiveRecord** - każdy model odpowiada jednej tabeli i zawiera metody do operacji na danych.

### Struktura modelu

Każdy model powinien zawierać:

| Element | Opis |
|---------|------|
| Prywatne pola | Reprezentują kolumny w tabeli (np. `$id`, `$subject`, `$content`) |
| Gettery/Settery | Publiczne metody dostępu do pól |
| `fromArray()` | Statyczna metoda tworząca obiekt z tablicy |
| `fill()` | Metoda wypełniająca obiekt danymi z tablicy |
| `findAll()` | Pobiera wszystkie rekordy |
| `find($id)` | Pobiera pojedynczy rekord po ID |
| `save()` | Zapisuje (INSERT lub UPDATE) rekord |
| `delete()` | Usuwa rekord |

### Przykład użycia

```php
use App\Model\Post;

// Pobierz wszystkie posty
$posts = Post::findAll();

// Znajdź post po ID
$post = Post::find(1);

// Utwórz nowy post
$post = new Post();
$post->setSubject('Tytuł');
$post->setContent('Treść');
$post->save();

// Usuń post
$post->delete();
```

### Konwencje nazewnictwa

- Nazwa modelu: `PascalCase`, rzeczownik w liczbie pojedynczej (np. `Post`, `Comment`, `UserProfile`)
- Nazwa tabeli: `snake_case`, liczba pojedyncza (np. `post`, `comment`, `user_profile`)
- Pola w modelu: `camelCase` (np. `$createdAt`, `$userId`)

---

## Controller

Kontrolery znajdują się w `src/Controller/` i zawierają logikę biznesową aplikacji.

### Odpowiedzialność kontrolera

- **Walidacja** danych wejściowych
- **Wywoływanie** metod modeli
- **Przygotowanie** danych dla widoków
- **Przekierowania** po akcjach modyfikujących dane

### Typowe metody kontrolera (CRUD)

```php
class PostController
{
    public function index(): array          // Lista wszystkich
    public function show(int $id): array    // Szczegóły jednego
    public function create(): array         // Formularz tworzenia
    public function store(array $data, Router $router): void   // Zapis nowego
    public function edit(int $id): array    // Formularz edycji
    public function update(int $id, array $data, Router $router): void // Aktualizacja
    public function delete(int $id, Router $router): void      // Usuwanie
}
```

### Ważne zasady

1. **Kontroler NIE wykonuje zapytań SQL** - deleguje to do modeli
2. **Kontroler NIE generuje HTML** - zwraca dane dla szablonów
3. **Metody zwracające dane** (GET) → zwracają tablicę z danymi
4. **Metody modyfikujące dane** (POST) → wykonują przekierowanie

---

## Routes (Routing)

Framework wykorzystuje **file-based routing** - struktura folderów w `src/routes/` odpowiada ścieżkom URL.

### Jak to działa?

| URL | Plik |
|-----|------|
| `/` | `src/routes/index.php` |
| `/posts` | `src/routes/posts/index.php` |
| `/posts/create` | `src/routes/posts/create/index.php` |
| `/category/edit` | `src/routes/category/edit/index.php` |

### Struktura pliku route

```php
<?php
/**
 * Route: /posts/create
 * GET -> formularz tworzenia nowego posta
 */

use App\Controller\PostController;

/** @var \App\Service\Router $router */

$controller = new PostController();

// Rozróżnienie metod HTTP
if ($router->isPost()) {
    // Obsługa formularza
    $controller->store($router->post('post'), $router);
    return null; // Przekierowanie w kontrolerze
}

// GET - wyświetl formularz
$data = $controller->create();

return [
    'template' => 'post/create',           // Nazwa szablonu (folder w templates/)
    'params' => array_merge($data, ['router' => $router]),  // Dane dla szablonu
    'title' => 'Nowy post',                 // Tytuł strony
    'bodyClass' => 'create',                // Klasa CSS dla <body>
];
```

### Dostępne metody routera

```php
$router->isGet()        // Czy żądanie GET?
$router->isPost()       // Czy żądanie POST?
$router->get('param')   // Pobierz parametr z URL (?param=value)
$router->post('field')  // Pobierz pole z formularza
$router->redirect('/path')  // Przekieruj na inny adres
```

---

## Templates

Szablony HTML znajdują się w `templates/`. Każdy szablon to folder zawierający `index.php` i opcjonalnie `style.css`.

### Struktura szablonów

```
templates/
├── layout/              # Główny layout (HTML, head, body)
│   └── index.php
├── 404/                 # Strona błędu 404
│   └── index.php
├── index/               # Strona główna
│   ├── index.php
│   └── style.css        # Opcjonalne style specyficzne dla strony
└── post/                # Szablony dla PostController
    ├── index/           # Lista postów
    │   └── index.php
    ├── show/            # Szczegóły posta
    │   └── index.php
    ├── create/          # Formularz tworzenia
    │   └── index.php
    └── edit/            # Formularz edycji
        └── index.php
```

### Layout (`templates/layout/index.php`)

Główny szablon definiuje strukturę HTML strony:

```php
<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/css/main.css">
    <?= getCollectedStyles() ?>  <!-- Style z komponentów -->
    <title><?= e($params['title']) ?></title>
</head>
<body class="<?= e($params['bodyClass']) ?>">
    <nav><!-- Nawigacja --></nav>
    <main><?= $params['main'] ?></main>  <!-- Treść strony -->
    <footer><!-- Stopka --></footer>
</body>
</html>
```

### Szablon widoku

```php
<!-- templates/post/index/index.php -->
<h1>Lista postów</h1>
<ul>
<?php foreach ($params['posts'] as $post): ?>
    <li>
        <a href="/posts?id=<?= $post->getId() ?>">
            <?= e($post->getSubject()) ?>
        </a>
    </li>
<?php endforeach; ?>
</ul>
```

### Funkcja `e()` - escapowanie

Zawsze używaj `e()` do wyświetlania danych użytkownika (ochrona przed XSS):
```php
<?= e($post->getContent()) ?>
```

---

## Components

Komponenty to reużywalne fragmenty interfejsu użytkownika. Znajdują się w folderze `components/` na poziomie głównym projektu.

### Struktura komponentu

```
components/
└── select-with-search/
    ├── index.php     # Kod PHP/HTML komponentu
    └── style.css     # Style CSS (automatycznie ładowane)
```

### Tworzenie komponentu

```php
<!-- components/button/index.php -->
<button class="btn <?= e($params['variant'] ?? 'primary') ?>">
    <?= e($params['label']) ?>
</button>
```

### Używanie komponentów

```php
<!-- W dowolnym szablonie lub komponencie -->
<?php component('button', ['label' => 'Zapisz', 'variant' => 'success']); ?>

<?php component('select-with-search', [
    'name' => 'category',
    'options' => $params['categories'],
]); ?>
```

### Automatyczne ładowanie stylów

Jeśli komponent ma plik `style.css`, zostanie on automatycznie dołączony do strony (zbierany przez `collectStyle()` i renderowany przez `getCollectedStyles()`).

---

### Konwencje

- Komponenty umieszczaj w `components/<nazwa>/index.php`
- Style komponentów umieszczaj w `components/<nazwa>/style.css`
- Szablony umieszczaj w `templates/<nazwa>/index.php`
- Style szablonów (opcjonalne) umieszczaj w `templates/<nazwa>/style.css`

---

## Migracje SQL

Folder `sql/` zawiera prosty system migracji bazodanowych.

### Konwencja nazewnictwa

```
sql/
├── 01-post.sql
├── 02-comment.sql
├── 03-user.sql
└── 99-fix-comments.sql
```

Format: `<numer>-<opis>.sql` lub `<data>-<opis>.sql`

### Zasady

1. **Nigdy nie modyfikuj** istniejących migracji - twórz nowe
3. **Przed commitem** sprawdź, czy nowe migracje są w repozytorium
4. **Po pullowaniu** wykonaj nowe migracje na swojej bazie

### Konwencje nazewnictwa tabel

- Rzeczownik w liczbie pojedynczej: `post`, `comment`, `user`
- Wielowyrazowe nazwy: `snake_case` (np. `blog_post`, `user_profile`)

---

## Front Controller

Plik `public/index.php` to jedyny punkt wejścia do aplikacji:

```php
<?php
require_once __DIR__ . '/../autoload.php';

use App\Service\Router;
use App\Exception\NotFoundException;

try {
    $router = new Router();
    $view = $router->dispatch();
    
    if ($view) {
        echo $view;
    }
} catch (NotFoundException $e) {
    http_response_code(404);
    echo $router->render404();
} catch (\Exception $e) {
    http_response_code(500);
    // Obsługa błędów...
}
```

---

## Licencja

MIT License - szczegóły w pliku [LICENSE](LICENSE)
