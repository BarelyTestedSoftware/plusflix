<?php
/**
 * Strona główna - Widok
 */

// TODO: Zamiast tego będą dane z bazy
$shows = [];
for ($i = 1; $i <= 6; $i++) {
    $show = new \App\Model\Show();
    $show->id = $i;
    $show->title = "Serial " . $i;
    $show->description = "Opis serialu " . $i;
    $show->type = 1;
    $show->productionDate = "202" . ($i % 3) . "-01-15";
    $show->numberOfEpisodes = 10 + ($i * 2);
    
    $coverImage = new \App\Model\Media();
    $coverImage->id = $i;
    $coverImage->src = "https://m.media-amazon.com/images/M/MV5BMDBmYTZjNjUtN2M1MS00MTQ2LTk2ODgtNzc2M2QyZGE5NTVjXkEyXkFqcGdeQXVyNzAwMjU2MTY@._V1_.jpg";
    $coverImage->alt = "Cover Serial " . $i;
    $show->coverImage = $coverImage;
    
    $backgroundImage = new \App\Model\Media();
    $backgroundImage->id = 100 + $i;
    $backgroundImage->src = "https://via.placeholder.com/1920x1080?text=Background+" . $i;
    $backgroundImage->alt = "Background Serial " . $i;
    $show->backgroundImage = $backgroundImage;
    
    $director = new \App\Model\Person();
    $director->id = $i;
    $director->name = "Reżyser " . $i;
    $director->type = 1;
    $show->director = $director;
    
    $show->actors = [];
    $show->streamings = [
        (new \App\Model\Streaming()),
        (new \App\Model\Streaming()),
    ];
    $show->categories = [];
    
    $rating = new \App\Model\Rating();
    $rating->id = $i;
    $rating->value = 80 + ($i * 2);
    $rating->show = $show;
    $show->ratings = [$rating];
    
    $shows[] = $show;
}

$heroMovie = [
        'title' => 'Oppenheimer',
        'year' => 2023,
        'duration' => 180,
        'desc' => 'Historia J. Roberta Oppenheimera i jego roli w rozwoju bomby atomowej.',
        'rating' => 8.9,
        'votes' => 215,
        'bg' => 'https://image.tmdb.org/t/p/original/fm6KqXpk3M2HVveHwCrBSSBaO0V.jpg'
];

$categories = [
        'NAJWYŻEJ OCENIANE',
        'ULUBIONE',
        'KOMEDIE'
];
?>

<div class="hero-banner" id="hero-banner" style="background-image: url(''); background-position: center top;">    <div class="hero-overlay"></div>
    <div class="hero-content">

        <div class="hero-tag" id="hero-category">Film</div>

        <h1 class="hero-title" id="hero-title">Oppenheimer</h1>

        <div class="hero-meta">
            <span id="hero-year">2023</span>
            <span>•</span>
            <span id="hero-duration">180 min</span>
        </div>

        <div class="rating-box" style="margin-bottom: 20px;">
            <span class="stars" id="hero-stars" style="color: #FFD700; letter-spacing: 2px;">★★★★☆</span>
        </div>

        <p class="hero-desc" id="hero-desc">
            Historia J. Roberta Oppenheimera i jego roli w rozwoju bomby atomowej.
        </p>

        <div class="hero-actions">
            <a href="/movie" class="btn-lg btn-primary-soft" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <span style="font-size: 18px;">▶</span> Zobacz szczegóły
            </a>
            <a href="/rate" class="btn btn-ghost btn-lg" style="text-decoration: none;">
                Dodaj ocenę
            </a>
        </div>

        <div class="slider-dots" id="slider-dots">
        </div>
    </div>
</div>

<div class="content-panel">
    <div class="lists-wrapper">
        <?php component('movie-list', ['shows' => $shows]) ?>
    </div>
</div>
