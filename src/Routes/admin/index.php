<?php

use App\Controller\CategoryController;
use App\Controller\PersonController;
use App\Controller\ShowController;
use App\Controller\StreamingController;
use App\Model\Category;
use App\Model\Person;
use App\Model\Show;
use App\Model\Streaming;

/**
 * Route: /admin
 * Wybór podstrony administracyjnej
 * 
 * @var \App\Service\Router $router
 */

$table = $_GET['table'] ?? null;
$data = [];
$table_column_names = [];
$header = 'Zarządzanie';

if ($table) {
    $delete_path = "/admin/$table/delete";
    $edit_path = "/admin/$table/edit";
    switch ($table) {
        case 'category':
            $controller = new CategoryController();
            $dataObj = $controller->getAll()['categories'];
            $data = array_map(fn($item) => $item instanceof Category ? $item->toArray() : $item, $dataObj);
            $table_column_names = ['ID', 'Nazwa'];
            $header = 'Lista kategorii';
            break;
        case 'person':
            $controller = new PersonController();
            $dataObj = $controller->getAll()['persons'];
            $data = array_map(fn($item) => $item instanceof Person ? $item->toArray() : $item, $dataObj);
            $table_column_names = ['ID', 'Imię', 'Nazwisko', 'Rola'];
            $header = 'Lista osób';
            break;
        case 'show':
            $controller = new ShowController();
            $dataObj = $controller->getAll()['shows'];
            $data = array_map(fn($item) => $item instanceof Show ? $item->toArray() : $item, $dataObj);
            $table_column_names = ['ID', 'Tytuł', 'Liczba sezonów'];
            $header = 'Lista filmów i seriali';
            break;
        case 'streaming':
            $controller = new StreamingController();
            $dataObj = $controller->getAll()['streamings'];
            $data = array_map(fn($item) => $item instanceof Streaming ? $item->toArray() : $item, $dataObj);
            $table_column_names = ['ID', 'Nazwa'];
            $header = 'Lista serwisów streamingowych';
            break;
    }

    if (!empty($data)) {
        ob_start();
        render_component('admin-table', [
            'router' => $router,
            'table_column_names' => $table_column_names,
            'data' => $data,
            'header' => $header,
            'delete_path' => $delete_path,
            'edit_path' => $edit_path,
        ]);
        $table_content = ob_get_clean();
    }
}

return [
    'template' => 'admin',
    'params' => [
        'router' => $router,
        'table_content' => $table_content ?? null,
    ],
    'title' => 'Zarządzanie',
    'bodyClass' => 'admin-page',
];