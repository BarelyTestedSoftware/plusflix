<?php
/** Route: /admin/show/add ...*/

use App\Controller\ShowController;

/** @var \App\Service\Router $router */

$controller = new ShowController();

if ($router->isPost()) {
    $controller->store($router->post('show'), $router);
    return null;
}

return [
    'template' => 'admin/show-form/index',
    'params' => [
        'show' => null,
        'router' => $router
    ],
    'title' => 'dodaj produkcję'
];