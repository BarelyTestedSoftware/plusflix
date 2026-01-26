<?php
/**
 * Route: /search
 * Strona wyszukiwania z filtrami
 */

use App\Controller\ShowController;
use App\Controller\PersonController;
use App\Controller\CategoryController;

// Pobierz parametry wyszukiwania i filtrów
$query = $router->get('q') ?? '';
$typeFilter = $router->get('type') ?? '';
$actorFilter = $router->get('actor') ?? '';
$directorFilter = $router->get('director') ?? '';
$yearFilter = $router->get('year') ?? '';
$ratingFilter = $router->get('rating') ?? '';
$genreFilter = $router->get('genre') ?? '';

// Przygotuj filtry do przesłania do controllera
$filters = [];
if ($query) $filters['q'] = $query;
if ($typeFilter) $filters['type'] = $typeFilter;
if ($actorFilter) $filters['actor'] = $actorFilter;
if ($directorFilter) $filters['director'] = $directorFilter;
if ($yearFilter) $filters['year'] = $yearFilter;
if ($ratingFilter) $filters['rating'] = $ratingFilter;
if ($genreFilter) $filters['genre'] = $genreFilter;

$showController = new ShowController();
$personController = new PersonController();
$categoryController = new CategoryController();

// Dane do listy wyników
$shows = $showController->search($filters)['shows'];

// Dane pomocnicze do filtrów
$allShows = $showController->getAll()['shows'];
$people = $personController->getAll()['persons'] ?? [];
$categories = $categoryController->getAll()['categories'] ?? [];

// Opcje typu
$typeOptions = [
    '' => 'Wszystkie',
    '1' => 'Film',
    '2' => 'Serial',
];

// Opcje aktorów i reżyserów
$actorOptions = ['' => 'Wszyscy aktorzy'];
$directorOptions = ['' => 'Wszyscy reżyserzy'];
foreach ($people as $person) {
    $personId = $person->getId();
    $personName = $person->getName() ?? '';
    $type = (int) ($person->getType() ?? 0);
    if ($type === 1) {
        $actorOptions[$personId] = $personName;
    } elseif ($type === 2) {
        $directorOptions[$personId] = $personName;
    }
}

// Opcje lat produkcji (na podstawie wszystkich produkcji)
$yearOptions = ['' => 'Wszystkie lata'];
$years = [];
foreach ($allShows as $show) {
    $date = $show->getProductionDate();
    if ($date) {
        $year = substr($date, 0, 4);
        if (ctype_digit($year)) {
            $years[(int)$year] = (int)$year;
        }
    }
}
krsort($years);
foreach ($years as $y) {
    $yearOptions[$y] = (string) $y;
}

// Opcje ocen
$ratingOptions = [
    '' => 'Wszystkie oceny',
    '4' => '4+ gwiazdek',
    '3' => '3+ gwiazdek',
    '2' => '2+ gwiazdek',
    '1' => '1+ gwiazdek',
];

// Opcje gatunków z bazy kategorii
$genreOptions = ['' => 'Wszystkie gatunki'];
foreach ($categories as $category) {
    $genreOptions[$category->getId()] = $category->getName();
}

return [
    'template' => 'search',
    'params' => [
        'router' => $router,
        'shows' => $shows,
        'query' => $query,
        'filters' => [
            'type' => $typeFilter,
            'actor' => $actorFilter,
            'director' => $directorFilter,
            'year' => $yearFilter,
            'rating' => $ratingFilter,
            'genre' => $genreFilter,
        ],
        'filterOptions' => [
            'type' => $typeOptions,
            'actor' => $actorOptions,
            'director' => $directorOptions,
            'year' => $yearOptions,
            'rating' => $ratingOptions,
            'genre' => $genreOptions,
        ]
    ],
    'title' => $query ? "Wyniki wyszukiwania: " . $query : 'Wyszukiwanie',
    'bodyClass' => 'search',
];
