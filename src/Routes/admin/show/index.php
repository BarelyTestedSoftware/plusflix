<?php
/**
 * Route: /category
 * GET
 * 
 */

use App\Controller\ShowController;
use App\Model\Show;

/** @var \App\Service\Router $router */

$controller = new ShowController();

// Pobierz listę produkcji (obiekty)
$showsObj = $controller->getAll()["shows"];

// Zamień na 2D przez toArray
$showaData = array_map(function ($cat) {
    if ($cat instanceof Show) {
        return $cat->toArray();
    }
    return $cat;
}, $showsObj);

// Opcjonalnie: jeśli przekazano id i GET, przefiltruj jeden rekord (np. do podglądu)
if ($router->isGet() && null !== $router->get('id')) {
    $id = (int) $router->get('id');
    $showaData = array_values(array_filter($showaData, fn($row) => (int)($row['id'] ?? 0) === $id));
}

return [
    'template' => 'admin-table',
    'params' => [
        'router' => $router,
        'table_column_names' => ['ID', 'Tytuł', 'opis', 'typ', 'data produkcji', 'ilość odcinków', 'okładka', 'zdjęcie tła', 'reżyser', 'aktorzy', 'streamingi', 'kategorie', 'średnia ocena', 'ilość ocen'],
        'data' => $showaData,
        'header' => 'Lista produkcji'
    ],
    'title' => 'produkcje',
    'bodyClass' => 'show-add',
];
