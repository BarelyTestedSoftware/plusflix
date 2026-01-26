<?php
/**
 * Route: /admin/person/edit
 * GET -> formularz edycji osoby
 * POST -> zapis edytowanej osoby
 */

use App\Controller\PersonController;

/** @var \App\Service\Router $router */

$controller = new PersonController();

if ($router->isGet()) {
    $id = $router->get('id');
    if (null !== $id) {
        $person = $controller->get($id)["person"];
        return [
            'template' => 'admin/person-form',
            'params' => ['router' => $router, 'person' => $person],
            'title' => 'Edytuj osobę',
            'bodyClass' => 'person-edit',
        ];
    }
}

if ($router->isPost()) {
    $id = $router->get('id');
    if (null !== $id) {
        $controller->update($id, $_POST, $router);
    }
    return null;
}
