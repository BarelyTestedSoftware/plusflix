<?php
/**
 * Route: /admin/category/delete
 * GET -> usunięcie kategorii
 * GET parameter: id
 */

use App\Controller\CategoryController;

/** @var \App\Service\Router $router */

$controller = new CategoryController();

if ($router->isGet()) {
    $controller->delete($router->get('id'), $router);
}

return null;