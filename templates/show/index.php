<?php
$show = $params['show'] ?? null;

if (! $show) {
    echo '<div class="container"><p>Brak danych do wyświetlenia.</p></div>';
    return;
}

$typeLabel = ($show->type ?? null) === 1 ? 'Film' : 'Serial';
$year = $show->productionDate ? substr($show->productionDate, 0, 4) : '';
$ratingValue = isset($show->rating) ? number_format((float) $show->rating, 1) : '';
?>

<?php component('movie-background', ['backgroundImage' => $show->backgroundImage]); ?>

<div class="container movie-details-wrapper">
    <div class="movie-details-grid">

        <div class="movie-poster-col">
              <img src="<?= e($show->coverImage->src) ?>"
                  alt="<?= e($show->coverImage->alt) ?>"
                 class="poster-image">
        </div>

        <div class="movie-info-col">

            <span class="badge-category"><?= e($typeLabel) ?></span>

            <h1 class="movie-title"><?= e($show->title) ?></h1>

            <div class="movie-meta">
                <?php if ($ratingValue !== ''): ?>
                    <div class="meta-item">
                        <span class="rating-stars"><i class="fas fa-star"></i></span>
                        <span class="rating-score"><?php echo $ratingValue; ?> / 5 <span><?php if (! empty($show->numberOfRatings)) { echo ' (' . (int) $show->numberOfRatings . ' ocen)'; } ?></span></span>
                    </div>
                <?php endif; ?>
                <?php if ($year): ?>
                    <div class="meta-item">
                        <span><i class="fas fa-calendar"></i> <?= e($year) ?></span>
                    </div>
                <?php endif; ?>
                <?php if (($show->type ?? null) !== 1 && ! empty($show->numberOfEpisodes)): ?>
                    <div class="meta-item">
                        <span><i class="fas fa-tv"></i> <?php echo (int) $show->numberOfEpisodes; ?> odcinków</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="movie-actions-row">
                <?php if (! empty($show->categories)): ?>
                    <?php foreach ($show->categories as $category): ?>
                        <span class="badge-category"><?= e($category->getName()) ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>

                <a href="/rate" class="btn btn-ghost btn-lg" style="text-decoration: none;">
                    Dodaj ocenę
                </a>
            </div>

            <?php if (! empty($show->streamings)): ?>
                <div class="platforms-section">
                    <span class="section-label section-heading-large">
                        Gdzie obejrzeć?
                    </span>

                    <div class="platforms-list">
                        <?php foreach ($show->streamings as $streaming): ?>
                            <div class="platform-icon" title="<?= e($streaming->name) ?>">
                                <?= e($streaming->logoImage->src) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (! empty($show->description)): ?>
                <p class="movie-description">
                    <?= e($show->description) ?>
                </p>
            <?php endif; ?>

            <div class="credits-grid">
                <?php if (! empty($show->director)): ?>
                    <div>
                        <span class="section-label">Reżyser</span>
                        <div class="credit-name"><?= e($show->director->name) ?></div>
                    </div>
                <?php endif; ?>
                <?php if (! empty($show->actors)): ?>
                    <div>
                        <span class="section-label">W rolach głównych</span>
                        <div class="credit-name"><?= e(implode(', ', array_map(fn($actor) => $actor->name ?? '', $show->actors))) ?></div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>