<?php
/**
 * Route: /admin/person/delete
 * GET -> usunięcie osoby
 * GET parameter: id
 */

use App\Controller\PersonController;

/** @var \App\Service\Router $router */

$controller = new PersonController();

if ($router->isGet()) {
    $controller->delete((int)$router->get('id'), $router);
}

return null;
