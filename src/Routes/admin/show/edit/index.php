<?php
/** Route: /admin/show/edit?id= ...*/

use App\Controller\ShowController;
use App\Controller\PersonController;
use App\Controller\CategoryController;
use App\Controller\StreamingController;

/** @var \App\Service\Router $router */

$controller = new ShowController();
$id = (int) $router->get('id');

if ($router->isPost()) {
    $controller->update($id, $router->post('show'), $router);
    return null;
}

$data = $controller->edit($id);


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
    'params' => ['router' => $router, 'show' => $data['show'], 'actors' => $actors, 'directors' => $directors, 'categories' => $categories, 'streamings' => $streamings],
    'title' => 'Edytuj Produkcję'
];