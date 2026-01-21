<?php
/** Route: /admin/show/add ...*/

use App\Controller\ShowController;

/** @var \App\Service\Router $router */

$controller = new ShowController();

if ($router->isPost()) {
    $controller->store($router->post('show'), $router);
    return null;
}


use App\Model\Person;
use App\Model\Category;
use App\Model\Streaming;


$actors = [];
$directors = [];
$categories = [];
$streamings = [];


$pdo = new PDO(\App\Service\Config::get('db_dsn'), \App\Service\Config::get('db_user'), \App\Service\Config::get('db_pass'));
// Pobierz aktorów (type=0)
$stmt = $pdo->query('SELECT id, name FROM person WHERE type = 1 ORDER BY name');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $actor = new Person();
    $actor->setId((int)$row['id']);
    $actor->setName($row['name']);
    $actor->setType(0);
    $actors[] = $actor;
}
// Pobierz reżyserów (type=1)
$stmt = $pdo->query('SELECT id, name FROM person WHERE type = 2 ORDER BY name');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $director = new Person();
    $director->setId((int)$row['id']);
    $director->setName($row['name']);
    $director->setType(1);
    $directors[] = $director;
}

// Pobierz kategorie
foreach (Category::findAll() as $cat) {
    $categories[] = $cat;
}

// Pobierz platformy streamingowe
$stmt = $pdo->query('SELECT id, name FROM streaming ORDER BY name');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $streaming = new Streaming();
    $streaming->setId((int)$row['id']);
    $streaming->setName($row['name']);
    $streamings[] = $streaming;
}

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
    'title' => 'dodaj produkcję'
];