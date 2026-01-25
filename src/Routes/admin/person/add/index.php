<?php
/**
 * Route: /admin/person/add
 * POST -> zapis nowej osoby
 */

use App\Controller\PersonController;

/** @var \App\Service\Router $router */

$controller = new PersonController();

if ($router->isPost()) {
    $controller->store($_POST, $router);
    return null;
}

return [
    'template' => 'admin/person-form',
    'params' => ['router' => $router],
    'title' => 'Dodaj osobę',
    'bodyClass' => 'person-add',
];
