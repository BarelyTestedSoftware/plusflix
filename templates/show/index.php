<?php
$show = $params['show'] ?? null;

if (! $show) {
    echo '<div class="container"><p>Brak danych do wyświetlenia.</p></div>';
    return;
}

$type = $show->getType();
$productionDate = $show->getProductionDate();
$ratingValue = $show->getRating() !== null ? number_format((float) $show->getRating(), 1) : '';
$typeLabel = $type === 1 ? 'Film' : 'Serial';
$year = $productionDate ? substr($productionDate, 0, 4) : '';
$numberOfEpisodes = $show->getNumberOfEpisodes();
$categories = $show->getCategories();
$streamings = $show->getStreamings();
$numberOfRatings = $show->getNumberOfRatings();
$backgroundImage = $show->getBackgroundImage();
$coverImage = $show->getCoverImage();
$description = $show->getDescription();
$director = $show->getDirector();
$actors = $show->getActors();
?>

<?php component('movie-background', ['backgroundImage' => $backgroundImage]); ?>

<div class="container movie-details-wrapper">
    <div class="movie-details-grid">

        <div class="movie-poster-col">
              <img src="<?= e($coverImage?->getSrc()) ?>"
                  alt="<?= e($coverImage?->getAlt()) ?>"
                 class="poster-image">
        </div>

        <div class="movie-info-col">

            <span class="badge-category"><?= e($typeLabel) ?></span>

            <h1 class="movie-title"><?= e($show->getTitle()) ?></h1>

            <div class="movie-meta">
                <?php if ($ratingValue !== ''): ?>
                    <div class="meta-item">
                        <span class="rating-stars"><i class="fas fa-star"></i></span>
                        <span class="rating-score"><?php echo $ratingValue; ?> / 5 <span><?php if (! empty($numberOfRatings)) { echo ' (' . (int) $numberOfRatings . ' ocen)'; } ?></span></span>
                    </div>
                <?php endif; ?>
                <?php if ($year): ?>
                    <div class="meta-item">
                        <span><i class="fas fa-calendar"></i> <?= e($year) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($type !== 1 && ! empty($numberOfEpisodes)): ?>
                    <div class="meta-item">
                        <span><i class="fas fa-tv"></i> <?php echo (int) $numberOfEpisodes; ?> odcinków</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="movie-actions-row">
                <?php if (! empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <span class="badge-category"><?= e($category->getName()) ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>

                <a href="/rate" class="btn btn-ghost btn-lg" style="text-decoration: none;">
                    Dodaj ocenę
                </a>
            </div>

            <?php if (! empty($streamings)): ?>
                <div class="platforms-section">
                    <span class="section-label section-heading-large">
                        Gdzie obejrzeć?
                    </span>

                    <div class="platforms-list">
                        <?php foreach ($streamings as $streaming): ?>
                            <div class="platform-icon" title="<?= e($streaming->getName()) ?>">
                                <?= e($streaming->getLogoImage()?->getSrc()) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (! empty($description)): ?>
                <p class="movie-description">
                    <?= e($description) ?>
                </p>
            <?php endif; ?>

            <div class="credits-grid">
                <?php if (! empty($director)): ?>
                    <div>
                        <span class="section-label">Reżyser</span>
                        <div class="credit-name"><?= e($director->getName()) ?></div>
                    </div>
                <?php endif; ?>
                <?php if (! empty($actors)): ?>
                    <div>
                        <span class="section-label">W rolach głównych</span>
                        <div class="credit-name"><?= e(implode(', ', array_map(fn($actor) => $actor->getName() ?? '', $actors))) ?></div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>