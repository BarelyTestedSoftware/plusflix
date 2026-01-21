<?php
/**
 * Route: /admin/person
 * GET - lista osób (aktorzy i reżyserzy)
 */

use App\Controller\PersonController;
use App\Model\Person;

/** @var \App\Service\Router $router */

$controller = new PersonController();
$peopleObj = $controller->getAll()["persons"] ?? [];

// Zamień na 2D przez toArray + sformatuj typ na etykietę
$peopleData = array_map(function ($p) {
    if ($p instanceof Person) {
        $row = $p->toArray();
        $typeId = (int)($row['type'] ?? 0);
        $typeLabel = $typeId === 2 ? 'Reżyser' : 'Aktor';
        $row['type'] = ['id' => $typeId, 'name' => $typeLabel];
        return $row;
    }
    return $p;
}, $peopleObj);

// Opcjonalnie filtrowanie jednego rekordu, jeśli przekazano id
if ($router->isGet() && null !== $router->get('id')) {
    $id = (int) $router->get('id');
    $peopleData = array_values(array_filter($peopleData, fn($row) => (int)($row['id'] ?? 0) === $id));
}

return [
    'template' => 'admin-table',
    'params' => [
        'router' => $router,
        'table_column_names' => ['ID', 'Imię i nazwisko', 'Typ'],
        'data' => $peopleData,
        'header' => 'Lista osób',
    ],
    'title' => 'osoby',
    'bodyClass' => 'people-list',
];
