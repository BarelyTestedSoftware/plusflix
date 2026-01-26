<?php
/** Route: /admin/show/add ...*/

use App\Controller\ShowController;
use App\Controller\PersonController;
use App\Controller\CategoryController;
use App\Controller\StreamingController;

/** @var \App\Service\Router $router */

$controller = new ShowController();

if ($router->isPost()) {
    $controller->store($router->post('show'), $router);
    return null;
}

$personController = new PersonController();
$categoryController = new CategoryController();
$streamingController = new StreamingController();

$persons = $personController->getAll()['persons'] ?? [];
$actors = array_values(array_filter($persons, fn($person) => $person->getType() === 1));
$directors = array_values(array_filter($persons, fn($person) => $person->getType() === 2));

$categories = $categoryController->getAll()['categories'] ?? [];

$streamings = $streamingController->getAll()['streamings'] ?? [];

return [
    'template' => 'admin/show-form',
    'params' => [
        'show' => null,
        'router' => $router,
        'actors' => $actors,
        'directors' => $directors,
        'categories' => $categories,
        'streamings' => $streamings
    ],
    'title' => 'Dodaj produkcję'
];