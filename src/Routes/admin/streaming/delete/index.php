<?php
/**
 * Route: /admin/streaming/delete
 * GET -> usunięcie platformy
 * GET parameter: id
 */

use App\Controller\StreamingController;

/** @var \App\Service\Router $router */

$controller = new StreamingController();

if ($router->isGet()) {
    $id = $router->get('id');
    if (null !== $id) {
        $controller->delete($id, $router);
    }
}

return null;
