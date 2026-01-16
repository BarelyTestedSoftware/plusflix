<?php
/** Route: /admin/show/edit?id= ...*/

use App\Controller\ShowController;

/** @var \App\Service\Router $router */

$controller = new ShowController();
$id = (int) $router->get('id');

if ($router->isPost()) {
    $controller->update($id, $router->post('show'), $router);
    return null;
}

$data = $controller->edit($id);


use App\Model\Person;
use App\Model\Category;
use App\Model\Streaming;

$pdo = new PDO(\App\Service\Config::get('db_dsn'), \App\Service\Config::get('db_user'), \App\Service\Config::get('db_pass'));
// Pobierz aktorów (type=0)
$stmt = $pdo->query('SELECT id, name FROM person WHERE type = 1 ORDER BY name');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $actor = new Person();
    $actor->id = (int)$row['id'];
    $actor->name = $row['name'];
    $actor->type = 0;
    $actors[] = $actor;
}
// Pobierz reżyserów (type=1)
$stmt = $pdo->query('SELECT id, name FROM person WHERE type = 2 ORDER BY name');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $director = new Person();
    $director->id = (int)$row['id'];
    $director->name = $row['name'];
    $director->type = 1;
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
    $streaming->id = (int)$row['id'];
    $streaming->name = $row['name'];
    $streamings[] = $streaming;
}

return [
    'template' => 'admin/show-form',
    'params' => ['router' => $router, 'show' => $data['show'], 'actors' => $actors, 'directors' => $directors, 'categories' => $categories, 'streamings' => $streamings],
    'title' => 'Edytuj Produkcję'
];