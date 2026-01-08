<?php
/**
 * Strona główna - Widok
 */


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
<?php

<div class="hero-banner" id="hero-banner" style="background-image: url(""); background-position: center top;">    <div class="hero-overlay"></div>
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

        <div class="movie-list-section">
            <div class="category-title">NAJWYŻEJ OCENIANE:</div>
            <div class="movies-grid">
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
            </div>
        </div>

        <div class="movie-list-section">
            <div class="category-title">ULUBIONE:</div>
            <div class="movies-grid">
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
            </div>
        </div>

        <div class="movie-list-section">
            <div class="category-title">KOMEDIE:</div>
            <div class="movies-grid">
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
                <div class="movie-placeholder"></div>
            </div>
        </div>

    </div>
</div>
