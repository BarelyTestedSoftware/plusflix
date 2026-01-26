<?php
/**
 * Strona główna - Widok
 */


$shows = $params['shows'] ?? [];
$highlightedShow = $params['highlightedShow'] ?? null;


if ($highlightedShow) {
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
}
?>

<?php if ($highlightedShow): ?>
    <?php component('movie-background', ['backgroundImage' => $backgroundImage]); ?>
<?php endif; ?>

<?php if ($highlightedShow): ?>
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
<?php else: ?>
    <div style="padding: 100px; text-align: center; color: white;">
        <h1>Witaj w Plusflix!</h1>
        <p>Baza filmów jest pusta. Dodaj pierwsze filmy w panelu administratora lub zaimportuj dane SQL.</p>
    </div>
<?php endif; ?>

<div class="content-panel">
    <div class="lists-wrapper">

        <?php
        $targetCategories = [
                'Sci-Fi i Fantasy' => 1,
                'Komedie'          => 2,
                'Dramaty'          => 3,
                'Horrory'          => 4
        ];

        foreach ($targetCategories as $catName => $catId):

            $categoryShows = array_filter($shows, function($show) use ($catId) {
                $showCats = $show->getCategories();
                if (empty($showCats)) return false;

                foreach ($showCats as $cat) {
                    if ($cat->getId() == $catId) return true;
                }
                return false;
            });

            if (empty($categoryShows)) continue;
            ?>

            <div class="movie-list-section">
                <?php component('movie-list', ['shows' => $categoryShows, "sectionTitle" => $catName]) ?>
            </div>

        <?php endforeach; ?>

    </div>
</div>