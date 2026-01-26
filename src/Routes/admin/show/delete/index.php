<?php
/** Route: /admin/show/delete?id= ...*/

use App\Controller\ShowController;

/** @var \App\Service\Router $router */

$controller = new ShowController();
if ($router->isGet() && $router->get('id')) {
    $id = (int) $router->get('id');
    $controller->delete($id, $router);
} else {
    $router->redirect('/admin/show');
}

return null;