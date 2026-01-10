<?php

/** @var \App\Model\Show $show */
$show = $params['show'];

?>

<div class="movie" style="background-image: url('<?php echo $show->coverImage->src; ?>');">
    <button class="movie-like" aria-label="Dodaj do ulubionych">♡</button>
    <div class="movie-info">
        <div class="movie-rating">
            <span class="star">★</span>
            <span class="rating-value"><?= $show->rating; ?></span>
            <span class="rating-count">(<?= $show->numberOfRatings; ?>)</span>
        </div>
        <h3 class="movie-title"><?= e($show->title); ?></h3>

        <div class="movie-meta">
            <span class="meta-item"><i class="fas fa-calendar"></i> <?php echo substr($show->productionDate, 0, 4); ?></span>
            <span class="meta-item"><i class="fas fa-tv"></i> <?php echo $show->numberOfEpisodes; ?> ser.</span>
            <span class="meta-item"><i class="fas fa-play-circle"></i> <?php echo count($show->streamings); ?> platforma</span>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.movie').forEach(movie => {
        movie.addEventListener('click', () => {
            window.location.href = '/show?id=<?= $show->id; ?>';
        });
    });
</script>