<?php
/**
 * Route: /category/add
 * POST -> zapis nowej kategorii
 */

use App\Controller\CategoryController;

/** @var \App\Service\Router $router */

$controller = new CategoryController();

if ($router->isPost()) {
    $controller->store($_POST, $router);
}

return [
    'template' => 'admin/category-form',
    'params' => ['router' => $router],
    'title' => 'dodaj kategorię',
    'bodyClass' => 'category-add',
];