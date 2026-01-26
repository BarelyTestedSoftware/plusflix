<?php
/**
 * Template: search
 * Strona wyszukiwania z filtrami
 */

$router = $params['router'];
$shows = $params['shows'] ?? [];
$query = $params['query'] ?? '';
$filters = $params['filters'] ?? [];
$filterOptions = $params['filterOptions'] ?? [];
?>

<div class="search-page">
    <form  method="GET" action="/search">
    <div class="search-bar-container">
        <?php component('search-bar'); ?>
    </div>   
    <div class="search-header">
        <h1><i class="fas fa-search"></i> Wyszukiwanie</h1>
    </div>

    <div class="search-filters">
        <div class="filters-grid">
            <!-- Typ (Film/Serial) -->
            <div class="filter-item">
                <label for="filter-type">
                    <i class="fas fa-film"></i> Typ
                </label>
                <?php component('select', [
                    'name' => 'type',
                    'options' => $filterOptions['type'],
                    'value' => $filters['type'],
                    'placeholder' => 'Wybierz typ...',
                    'onchange' => 'this.form.submit()'
                ]); ?>
            </div>

            <!-- Gatunek -->
            <div class="filter-item">
                <label for="filter-genre">
                    <i class="fas fa-masks-theater"></i> Gatunek
                </label>
                <?php component('select-with-search', [
                    'name' => 'genre',
                    'options' => $filterOptions['genre'],
                    'selected' => $filters['genre'] ?? '',
                    'placeholder' => 'Wybierz gatunek...',
                    'allowCustom' => false,
                    'multiple' => false,
                ]); ?>
            </div>

            <!-- Rok produkcji -->
            <div class="filter-item">
                <label for="filter-year">
                    <i class="fas fa-calendar"></i> Rok produkcji
                </label>
                <?php component('select', [
                    'name' => 'year',
                    'options' => $filterOptions['year'],
                    'value' => $filters['year'],
                    'placeholder' => 'Wybierz rok...',
                    'onchange' => 'this.form.submit()'
                ]); ?>
            </div>

            <!-- Ocena -->
            <div class="filter-item">
                <label for="filter-rating">
                    <i class="fas fa-star"></i> Ocena
                </label>
                <?php component('select', [
                    'name' => 'rating',
                    'options' => $filterOptions['rating'],
                    'value' => $filters['rating'],
                    'placeholder' => 'Minimalna ocena...',
                    'onchange' => 'this.form.submit()'
                ]); ?>
            </div>

            <!-- Aktorzy -->
            <div class="filter-item">
                <label for="filter-actor">
                    <i class="fas fa-user"></i> Aktor
                </label>
                <?php component('select-with-search', [
                    'name' => 'actor',
                    'options' => $filterOptions['actor'],
                    'selected' => $filters['actor'] ?? '',
                    'placeholder' => 'Wybierz aktora...',
                    'allowCustom' => false,
                    'multiple' => false,
                ]); ?>
            </div>

            <!-- Reżyser -->
            <div class="filter-item">
                <label for="filter-director">
                    <i class="fas fa-video"></i> Reżyser
                </label>
                <?php component('select-with-search', [
                    'name' => 'director',
                    'options' => $filterOptions['director'],
                    'selected' => $filters['director'] ?? '',
                    'placeholder' => 'Wybierz reżysera...',
                    'allowCustom' => false,
                    'multiple' => false,
                ]); ?>
            </div>


        </div>
        <div class="filter-item filter-clear">
            <label>&nbsp;</label>
            <a href="/search<?= $query ? '?q=' . urlencode($query) : '' ?>" class="btn-clear-filters">
                <i class="fas fa-times"></i> Wyczyść filtry
            </a>
        </div>
    </div>
    </form>

    <script>
    (function() {
        const form = document.querySelector('.search-page form');
        if (!form) return;
        document.addEventListener('click', function(e) {
            const opt = e.target.closest('.select-with-search__option');
            if (opt && form.contains(opt)) {
                // allow component to update hidden input first
                setTimeout(() => form.submit(), 0);
            }
        });
    })();
    </script>

    <div class="search-results">
        <h2 class="results-count">
            <?php if ($shows): ?>
                Znaleziono <?= count($shows) ?> <?= count($shows) === 1 ? 'wynik' : 'wyników' ?>
            <?php else: ?>
                Brak wyników
            <?php endif; ?>
        </h2>

        <?php if ($shows): ?>
            <?php component('movie-list', ['shows' => $shows, 'sectionTitle' => 'Wyniki wyszukiwania', 'multiline' => true]); ?>
        <?php else: ?>
            <div class="no-results">
                <i class="fas fa-search"></i>
                <p>Nie znaleziono filmów ani seriali spełniających kryteria wyszukiwania.</p>
                <p>Spróbuj zmienić filtry lub wyszukiwane hasło.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
