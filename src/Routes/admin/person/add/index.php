<?php
/**
 * Route: /admin/person/add
 * POST -> zapis nowej osoby
 */

use App\Controller\PersonController;

/** @var \App\Service\Router $router */

$controller = new PersonController();

if ($router->isPost()) {
    // Przekazujemy płaski POST z polami: name, type
    $controller->store($_POST, $router);
}

return [
    'template' => 'admin/person-form',
    'params' => ['router' => $router],
    'title' => 'dodaj osobę',
    'bodyClass' => 'person-add',
];
