# AI Development Guide - Custom PHP Framework

## Architecture Overview

This is a **file-based routing MVC framework** for PHP with component-based UI rendering. Routes map directly to filesystem structure - `src/Routes/category/add/index.php` handles `/category/add`.

### Request Flow
1. `public/index.php` (front controller) → `Router::dispatch()` 
2. Router maps URL to `src/Routes/{path}/index.php`
3. Route file returns array with `template`, `params`, `title`, `bodyClass`
4. Router renders template → layout → HTML output with auto-collected CSS

### Core Pattern: Route Files
Routes are **not classes** - they're procedural PHP files that return render config:

```php
// src/Routes/show/index.php
$show = new \App\Model\Show();
$show->title = 'Oppenheimer';
// ... build object

return [
    'template' => 'show',                    // maps to templates/show/index.php
    'params' => ['router' => $router, 'show' => $show],
    'title' => 'Szczegóły: Oppenheimer',
    'bodyClass' => 'show',
];
```

**Always pass `$router` in params** - templates need it for `isGet()`, `isPost()`, `get()`, `post()` methods.

## Component System

Components live in `components/` (NOT `src/`). Each component is a folder with `index.php` + optional `style.css`:

```php
// Render component from any template/component
<?php component('movie-background', ['backgroundImage' => $media]); ?>
```

**CSS auto-loading**: If `style.css` exists, it's automatically collected and injected into `<head>` via `getCollectedStyles()`. Templates work the same way.

## HTML Escaping Convention

**Always use `e()` for dynamic output**:
```php
<?= e($show->title) ?>              // ✓ Correct
<?= htmlspecialchars(...) ?>        // ✗ Don't use manually
<?= $show->title ?>                 // ✗ XSS vulnerability
```

The `e()` function handles `null`/empty checks automatically - **remove redundant `?? ''`**:
```php
<?= e($show->title) ?>              // ✓ Already handles null
<?= e($show->title ?? '') ?>        // ✗ Redundant
```

## Model Conventions (ActiveRecord)

Models use **private properties** with getters/setters:
```php
class Category {
    private ?int $id = null;
    
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): Category { $this->id = $id; return $this; }
    
    public static function find($id): ?Category { /* PDO query */ }
    public function save(): void { /* INSERT or UPDATE */ }
}
```

Note: `Show` model uses **public properties** (legacy inconsistency) - when creating new models, follow Category pattern.

## Development Workflow

**Start server**: `php -S localhost:3000 -t public`

**Database**: SQLite file at root `data.db` - managed via `sql/*.sql` migrations (manual execution, no migration runner).

**Configuration**: Copy `config/config.dist.php` → `config/config.php` (gitignored). Access via `Config::get('db_dsn')`.

## Font Awesome & Styling

Project uses Font Awesome icons. Replace emoji with FA classes:
```php
<i class="fas fa-star"></i>         // ✓ Use this
📅                                   // ✗ Don't use emoji
```

Main CSS at `public/assets/css/main.css`. Component/template CSS automatically collected into `<style>` tags in document head.

## Project-Specific Patterns

**URL parameters**: Use `$router->get('id')` not `$_GET['id']`
```php
if ($router->get('id')) {
    $show = Show::find($router->get('id'));
}
```

**POST handling in routes**:
```php
if ($router->isPost()) {
    $controller->store($router->post('field'), $router);
    return null; // Controller handles redirect
}
```

**Type checking**: Use `($show->type ?? null) === 1` for "Film" vs "Serial"

**Dynamic content**: Prepare mock data in route files (see `src/Routes/index.php` for examples) - real DB queries come later.

## Common Tasks

**Add new route**: Create `src/Routes/{path}/index.php` that returns render array
**Add new component**: Create `components/{name}/index.php` + `style.css`
**Add new template**: Create `templates/{name}/index.php` + `style.css`
**Add new model**: Follow `src/Model/Category.php` pattern (private fields + getters/setters + ActiveRecord methods)

**Debugging**: Check `public/index.php` try-catch - errors display when `display_errors` is on.
