<?php
/**
 * Route: /admin/category
 * GET - wyświetlenie konkretnego rekordu lub listy kategorii
 * 
 */

use App\Controller\CategoryController;
use App\Model\Category;


/** @var \App\Service\Router $router */

$controller = new CategoryController();
// Pobierz listę kategorii (obiekty)
$categoriesObj = $controller->getAll()["categories"];

// Zamień na 2D przez toArray
$categoriesData = array_map(function ($cat) {
    if ($cat instanceof Category) {
        return $cat->toArray();
    }
    return $cat;
}, $categoriesObj);

// Opcjonalnie: jeśli przekazano id i GET, przefiltruj jeden rekord (np. do podglądu)
if ($router->isGet() && null !== $router->get('id')) {
    $id = (int) $router->get('id');
    $categoriesData = array_values(array_filter($categoriesData, fn($row) => (int)($row['id'] ?? 0) === $id));
}

return [
    'template' => 'admin-table',
    'params' => [
        'router' => $router,
        'table_column_names' => ['ID', 'Nazwa'],
        'data' => $categoriesData,
        'header' => 'Lista kategorii',
    ],
    'title' => 'kategorie',
    'bodyClass' => 'category-add',
];
