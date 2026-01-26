<?php
/**
 * Route: /search
 * Strona wyszukiwania z filtrami
 */

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

// Użyj ShowController do wyszukiwania i filtrowania
$showController = new \App\Controller\ShowController();
$shows = $showController->search($filters)['shows'];

// TODO: Pobierać z bazy danych zamiast hardkodowania
// Przygotuj listy opcji dla filtrów
$typeOptions = [
    '' => 'Wszystkie',
    '1' => 'Film',
    '2' => 'Serial'
];

$actorOptions = ['' => 'Wszyscy aktorzy'];
for ($i = 0; $i < 10; $i++) {
    $actorOptions[(100 + $i)] = 'Aktor ' . chr(65 + $i);
}

$directorOptions = ['' => 'Wszyscy reżyserzy'];
for ($i = 0; $i < 10; $i++) {
    $directorOptions[($i + 1)] = 'Reżyser ' . chr(65 + $i);
}

$yearOptions = ['' => 'Wszystkie lata'];
for ($year = 2025; $year >= 2015; $year--) {
    $yearOptions[$year] = (string)$year;
}

$ratingOptions = [
    '' => 'Wszystkie oceny',
    '4' => '4+ gwiazdek',
    '3' => '3+ gwiazdek',
    '2' => '2+ gwiazdek',
    '1' => '1+ gwiazdek'
];

$genreOptions = [
    '' => 'Wszystkie gatunki',
    '1' => 'Akcja',
    '2' => 'Dramat',
    '3' => 'Komedia',
    '4' => 'Thriller',
    '5' => 'Sci-Fi'
];

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
