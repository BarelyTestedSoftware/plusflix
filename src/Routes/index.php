<?php
/**
 * Route: /
 * Strona główna
 */

// TODO: Zamiast tego będą dane z bazy
$shows = [];
for ($i = 1; $i <= 6; $i++) {
    $show = new \App\Model\Show();
    $show->setId($i);
    $show->setTitle("Serial " . $i);
    $show->setDescription("Opis serialu " . $i);
    $show->setType(1);
    $show->setProductionDate("202" . ($i % 3) . "-01-15");
    $show->setNumberOfEpisodes(10 + ($i * 2));
    
    $coverImage = new \App\Model\Media();
    $coverImage->id = $i;
    $coverImage->src = "https://m.media-amazon.com/images/M/MV5BMDBmYTZjNjUtN2M1MS00MTQ2LTk2ODgtNzc2M2QyZGE5NTVjXkEyXkFqcGdeQXVyNzAwMjU2MTY@._V1_.jpg";
    $coverImage->alt = "Cover Serial " . $i;
    $show->setCoverImage($coverImage);
    
    $backgroundImage = new \App\Model\Media();
    $backgroundImage->id = 100 + $i;
    $backgroundImage->src = "https://www.hindustantimes.com/ht-img/img/2023/07/22/550x309/oppenheimer_1690033428720_1690033428887.jpg";
    $backgroundImage->alt = "Background Serial " . $i;
    $show->setBackgroundImage($backgroundImage);
    
    $director = new \App\Model\Person();
    $director->id = $i;
    $director->name = "Reżyser " . $i;
    $director->type = 1;
    $show->setDirector($director);

    $show->setActors([]);
    $show->setStreamings([
        (new \App\Model\Streaming()),
        (new \App\Model\Streaming()),
    ]);
    $show->setCategories([]);

    $show->setRating(rand(40, 50) / 10);
    $show->setNumberOfRatings(rand(50, 500));
    
    $shows[] = $show;
}

// Przykładowe dane pokazu do widoku „show” 
// TODO: W przyszłości pobierać z bazy danych na podstawie ID z URL
$show = new \App\Model\Show();
$show->setId(1);
$show->setTitle('Oppenheimer');
$show->setDescription('Historia J. Roberta Oppenheimera i jego roli w rozwoju bomby atomowej.');
$show->setType(1); // 1 = film
$show->setProductionDate('2023-07-21');
$show->setNumberOfEpisodes(1);

$coverImage = new \App\Model\Media();
$coverImage->id = 1;
$coverImage->src = 'https://image.tmdb.org/t/p/w600_and_h900_bestv2/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg';
$coverImage->alt = 'Plakat Oppenheimer';
$show->setCoverImage($coverImage);

$backgroundImage = new \App\Model\Media();
$backgroundImage->id = 2;
$backgroundImage->src = 'https://www.hindustantimes.com/ht-img/img/2023/07/22/550x309/oppenheimer_1690033428720_1690033428887.jpg';
$backgroundImage->alt = 'Tło Oppenheimer';
$show->setBackgroundImage($backgroundImage);

$director = new \App\Model\Person();
$director->id = 1;
$director->name = 'Christopher Nolan';
$director->type = 1;
$show->setDirector($director);

$actors = [];
foreach ([
    ['id' => 10, 'name' => 'Cillian Murphy'],
    ['id' => 11, 'name' => 'Emily Blunt'],
    ['id' => 12, 'name' => 'Matt Damon'],
] as $actorData) {
    $actor = new \App\Model\Person();
    $actor->id = $actorData['id'];
    $actor->name = $actorData['name'];
    $actor->type = 0;
    $actors[] = $actor;
}
$show->setActors($actors);

$streamings = [];
foreach ([
    ['id' => 1, 'name' => 'Netflix', 'logo' => 'N'],
    ['id' => 2, 'name' => 'HBO Max', 'logo' => 'H'],
    ['id' => 3, 'name' => 'Apple TV+', 'logo' => 'A'],
    ['id' => 4, 'name' => 'Prime Video', 'logo' => 'P'],
] as $streamingData) {
    $streaming = new \App\Model\Streaming();
    $streaming->id = $streamingData['id'];
    $streaming->name = $streamingData['name'];

    $logo = new \App\Model\Media();
    $logo->id = 100 + $streamingData['id'];
    $logo->src = $streamingData['logo']; // uproszczony placeholder
    $logo->alt = $streamingData['name'];
    $streaming->logoImage = $logo;

    $streamings[] = $streaming;
}
$show->setStreamings($streamings);

$categories = [];
foreach ([
    ['id' => 1, 'name' => 'Dramat'],
    ['id' => 2, 'name' => 'Biografia'],
] as $categoryData) {
    $category = new \App\Model\Category();
    $category->setId($categoryData['id']);
    $category->setName($categoryData['name']);
    $categories[] = $category;
}

$show->setCategories($categories);

$show->setRating(4.0);
$show->setNumberOfRatings(1287);

return [
    'template' => 'index',
    'params' => ['router' => $router, 'shows' => $shows, 'highlightedShow' => $show],
    'title' => 'Plusflix',
    'bodyClass' => 'index',

];