<?php
/**
 * Route: /show?id=...
 * To jest plik LOGIKI. Pobiera dane i przekazuje je do widoku.
 */

use App\Controller\ShowController;

// 1. Pobierz ID z adresu URL
$id = $_GET['id'] ?? 0;

// 2. Jeśli brak ID, wróć na stronę główną
if (!$id) {
    header('Location: /');
    exit;
}

// 3. Użyj Kontrolera, żeby pobrać film z bazy
$controller = new ShowController();
$data = $controller->get((int)$id);

// 4. Wyślij dane do pliku widoku (templates/show/index.php)
return [
    'template' => 'show', // To wskazuje na folder templates/show
    'title' => $data['show']->getTitle() . ' - Plusflix',
    'params' => [
        'router' => $router,
        'show' => $data['show'] // Przekazujemy film do widoku
    ],
    'bodyClass' => 'show-page',
];