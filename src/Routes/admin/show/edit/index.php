<?php
/** Route: /admin/show/edit?id= ...*/

use App\Controller\ShowController;

/** @var \App\Service\Router $router */

$controller = new ShowController();
$id = (int) $router->get('id');

if ($router->isPost()) {
    $controller->update($id, $router->post('show'), $router);
    return null;
}

$data = $controller->edit($id);

return [
    'template' => 'admin/show-form',
    'params' => array_merge($data, ['router' => $router]),
    'title' => 'Edytuj Produkcję'
];