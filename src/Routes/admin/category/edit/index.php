<?php
/**
 * Route: /admin/category/edit
 * GET -> formularz edycji kategorii
 * POST -> zapis edytowanej kategorii
 */

use App\Controller\CategoryController;

/** @var \App\Service\Router $router */

$controller = new CategoryController();

if ($router->isGet()) {
    $id = $router->get('id');
    if (null !== $id) {
        $category = $controller->get($id)["category"];
        return [
            'template' => 'admin/category-form',
            'params' => ['router' => $router, 'category' => $category],
            'title' => 'edytuj kategorię',
            'bodyClass' => 'category-edit',
        ];
    }

}

if ($router->isPost()) {
    $id = $router->get('id');
    if (null !== $id) {
        $controller->update($id, $_POST, $router);
    }
}
