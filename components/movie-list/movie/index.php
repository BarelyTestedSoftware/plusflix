<?php

/** @var \App\Model\Show $show */
$show = $params['show'];
$nStreamings = count($show->streamings);

?>

<a href="/show?id=<?= $show->id ?>" class="movie" style="background-image: url('<?= e($show->coverImage->src); ?>');">
    <button class="movie-like">♡</button>
    <div class="movie-info">
        <div class="movie-rating">
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="rating-value"><?= e($show->rating); ?></span>
            <span class="rating-count">(<?= e($show->numberOfRatings); ?>)</span>
        </div>
        <h3 class="movie-title"><?= e($show->title); ?></h3>

        <div class="movie-meta">
            <span class="meta-item"><i class="fas fa-calendar"></i> <?= e(substr($show->productionDate, 0, 4)); ?></span>
            <?php if (($show->type ?? null) === 2): ?>
                <span class="meta-item"><i class="fas fa-tv"></i> <?= e($show->numberOfEpisodes); ?> odc.</span>
            <?php endif; ?>
            <span class="meta-item"><i class="fas fa-play-circle"></i> <?= e($nStreamings); ?> <?= $nStreamings == 1 ? 'platforma' : 'platformy' ?></span>
        </div>
    </div>
</a>