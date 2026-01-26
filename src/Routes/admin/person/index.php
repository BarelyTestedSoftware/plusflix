<?php
/**
 * Route: /admin/person
 * GET - wyświetlenie listy osób
 */

use App\Controller\PersonController;
use App\Model\Person;

/** @var \App\Service\Router $router */

$controller = new PersonController();
$personsObj = $controller->getAll()["persons"];

$personsData = array_map(function ($person) {
    $row = $person->toArray();

    if (isset($row['type'])) {
        if ((int) $row['type'] === 1) {
            $row['type'] = 'Aktor';
        } elseif ((int) $row['type'] === 2) {
            $row['type'] = 'Reżyser';
        }
    }

    return $row;
}, $personsObj);
if ($router->isGet() && null !== $router->get('id')) {
    $id = (int) $router->get('id');
    $personsData = array_values(array_filter($personsData, fn($row) => (int)($row['id'] ?? 0) === $id));
}

return [
    'template' => 'admin-table',
    'params' => [
        'router' => $router,
        'table_column_names' => ['ID', 'Imię i nazwisko', 'Rola'],
        'data' => $personsData,
        'header' => 'Lista osób',
    ],
    'title' => 'Osoby',
    'bodyClass' => 'person-list',
];
