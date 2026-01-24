<?php
/**
 * Strona główna - Widok
 */
$shows = $params['shows'];
$highlightedShow = $params['highlightedShow'];

$type = $highlightedShow->getType();
$productionDate = $highlightedShow->getProductionDate();
$rating = $highlightedShow->getRating();
$ratingValue = $rating !== null ? number_format((float) $rating, 1) : '';
$typeLabel = $type === 1 ? 'Film' : 'Serial';
$year = $productionDate ? substr($productionDate, 0, 4) : '';
$numberOfEpisodes = $highlightedShow->getNumberOfEpisodes();
$numberOfRatings = $highlightedShow->getNumberOfRatings();
$backgroundImage = $highlightedShow->getBackgroundImage();
$coverImage = $highlightedShow->getCoverImage();
$description = $highlightedShow->getDescription();
?>

<?php component('movie-background', ['backgroundImage' => $backgroundImage]); ?>

<div class="hero-banner" id="hero-banner">
    <div class="hero-content">

        <div class="hero-tag" id="hero-category"><?= e($typeLabel) ?></div>

        <h1 class="hero-title" id="hero-title"><?= e($highlightedShow->getTitle()) ?></h1>

        <div class="hero-meta">
            <?php if ($ratingValue !== ''): ?>
                <div class="meta-item">
                    <span class="rating-stars"><i class="fas fa-star"></i></span>
                    <span class="rating-score"><?php echo $ratingValue; ?> / 5 <?php if (! empty($numberOfRatings)) { echo ' (' . (int) $numberOfRatings . ' ocen)'; } ?></span>
                </div>
            <?php endif; ?>
            <?php if ($year): ?>
                <div class="meta-item">
                    <span><i class="fas fa-calendar"></i> <?= e($year) ?></span>
                </div>
            <?php endif; ?>
            <?php if ($type !== 1 && !empty($numberOfEpisodes)): ?>
                <div class="meta-item">
                    <span><i class="fas fa-tv"></i> <?= (int) $numberOfEpisodes ?> odcinków</span>
                </div>
            <?php endif; ?>
        </div>

        <p class="hero-desc" id="hero-desc">
            <?= e($description) ?>
        </p>

        <div class="hero-actions">
            <a href="/show?id=<?= $highlightedShow->getId() ?>" class="btn-lg btn-primary-soft" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
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

        <?php
        $categories = [
                'Dramaty' => array_slice($shows, 0, 6),
                'Sci-Fi i Fantasy' => array_slice($shows, 0, 6),
                'Komedie'          => array_slice($shows, 0, 6),
                'Horrory'          => array_slice($shows, 0, 6),
        ];
        ?>

        <?php foreach ($categories as $categoryName => $categoryShows): ?>

            <div class="movie-list-section">
                <div class="category-title">
                    <?= htmlspecialchars($categoryName) ?>
                </div>

                <?php component('movie-list', ['shows' => $categoryShows]) ?>
            </div>

        <?php endforeach; ?>

    </div>
</div>

