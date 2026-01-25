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
    $id = $router->get('id');
    if (null !== $id) {
        $controller->delete($id, $router);
    }
}

return null;
