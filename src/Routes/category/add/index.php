<?php
/**
 * Route: /category/add
 * POST -> zapis nowej kategorii
 */

use App\Controller\CategoryController;

/** @var \App\Service\Router $router */

$controller = new CategoryController();

if ($router->isPost()) {
    $controller->store($router->post('category'), $router);
}

return null;