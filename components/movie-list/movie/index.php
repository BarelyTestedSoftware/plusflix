<?php

/** @var \App\Model\Show $show */
$show = $params['show'];

?>

<div class="movie" style="background-image: url('<?php echo $show->coverImage->src; ?>');">
    <button class="movie-like">♡</button>
    <div class="movie-info">
        <h3 class="movie-title"><?php echo htmlspecialchars($show->title); ?></h3>
        <div class="movie-rating">
            <span class="star">★</span>
            <span class="rating-value"><?php echo number_format($show->ratings[0]->value / 20, 1); ?></span>
            <span class="rating-count">(<?php echo count($show->ratings); ?>)</span>
        </div>
        <div class="movie-meta">
            <span class="meta-item"><i class="fas fa-calendar"></i> <?php echo substr($show->productionDate, 0, 4); ?></span>
            <span class="meta-item"><i class="fas fa-tv"></i> <?php echo $show->numberOfEpisodes; ?> ser.</span>
            <span class="meta-item"><i class="fas fa-play-circle"></i> <?php echo count($show->streamings); ?> platforma</span>
        </div>
    </div>
</div>