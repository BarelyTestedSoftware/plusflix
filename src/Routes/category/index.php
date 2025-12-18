<?php
/**
 * Route: /category
 * GET
 * 
 */

use App\Controller\CategoryController;

/** @var \App\Service\Router $router */

$controller = new CategoryController();

// GET - wyświetl formularz
$categories = $controller->getAll();

if( null !== $router->get('id') && $router->isGet()) {
    $category = $controller->get($router->get('id'));
    return [
    'template' => 'category',
    'params' => ['router' => $router, 'categories' => $category],
    'title' => 'kategorie',
    'bodyClass' => 'category-add',
];

}

return [
    'template' => 'category',
    'params' => array_merge($categories, ['router' => $router]),
    'title' => 'kategorie',
    'bodyClass' => 'category-add',
];
