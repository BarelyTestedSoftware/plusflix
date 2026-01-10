<?php
/**
 * Strona główna - Widok
 */
$shows = $params['shows'];
$highlightedShow = $params['highlightedShow'];

$typeLabel = ($highlightedShow->type ?? null) === 1 ? 'Film' : 'Serial';
$year = $highlightedShow->productionDate ? substr($highlightedShow->productionDate, 0, 4) : '';
$ratingValue = isset($highlightedShow->rating) ? number_format((float) $highlightedShow->rating, 1) : '';
?>

<?php component('movie-background', ['backgroundImage' => $highlightedShow->backgroundImage]); ?>

<div class="hero-banner" id="hero-banner">
    <div class="hero-poster">
        <img src="<?= e($highlightedShow->coverImage->src) ?>" 
             alt="<?= e($highlightedShow->coverImage->alt) ?>" 
             class="hero-poster-image">
    </div>
    
    <div class="hero-content">

        <div class="hero-tag" id="hero-category"><?= e($typeLabel) ?></div>

        <h1 class="hero-title" id="hero-title"><?= e($highlightedShow->title) ?></h1>

        <div class="hero-meta">
            <?php if ($ratingValue !== ''): ?>
                <div class="meta-item">
                    <span class="rating-stars"><i class="fas fa-star"></i></span>
                    <span class="rating-score"><?php echo $ratingValue; ?> / 5 <?php if (! empty($highlightedShow->numberOfRatings)) { echo ' (' . (int) $highlightedShow->numberOfRatings . ' ocen)'; } ?></span>
                </div>
            <?php endif; ?>
            <?php if ($year): ?>
                <div class="meta-item">
                    <span><i class="fas fa-calendar"></i> <?= e($year) ?></span>
                </div>
            <?php endif; ?>
            <?php if (($highlightedShow->type ?? null) !== 1 && !empty($highlightedShow->numberOfEpisodes)): ?>
                <div class="meta-item">
                    <span><i class="fas fa-tv"></i> <?= (int) $highlightedShow->numberOfEpisodes ?> sezonów</span>
                </div>
            <?php endif; ?>
        </div>

        <p class="hero-desc" id="hero-desc">
            <?= e($highlightedShow->description) ?>
        </p>

        <div class="hero-actions">
            <a href="/show?id=<?= $highlightedShow->id ?>" class="btn-lg btn-primary-soft" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
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
