<?php

/** @var \App\Model\Show $show */
$show = $params['show'];
$streamings = $show->getStreamings();
$nStreamings = count($streamings);
$coverImage = $show->getCoverImage();
$rating = $show->getRating();
$numberOfRatings = $show->getNumberOfRatings();
$productionDate = $show->getProductionDate();
$type = $show->getType();
$numberOfEpisodes = $show->getNumberOfEpisodes();

?>

<a href="/show?id=<?= $show->getId() ?>" class="movie" style="background-image: url('<?= e($coverImage?->getSrc()); ?>');">
    <button class="movie-like" aria-label="Dodaj do ulubionych">♡</button>
    <div class="movie-info">
        <div class="movie-rating">
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="rating-value"><?= e(number_format($rating, 1)); ?></span>
            <span class="rating-count">(<?= e($numberOfRatings); ?>)</span>
        </div>
        <h3 class="movie-title"><?= e($show->getTitle()); ?></h3>

        <div class="movie-meta">
            <span class="meta-item"><i class="fas fa-calendar"></i> <?= e(substr($productionDate, 0, 4)); ?></span>
            <?php if ($type === 2): ?>
                <span class="meta-item"><i class="fas fa-tv"></i> <?= e($numberOfEpisodes); ?> odc.</span>
            <?php endif; ?>
            <span class="meta-item"><i class="fas fa-play-circle"></i> <?= e($nStreamings); ?> <?= $nStreamings == 1 ? 'platforma' : 'platformy' ?></span>
        </div>
    </div>
</a>