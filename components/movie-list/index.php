<?php

/** @var \App\Model\Show[] $shows */
$shows = $params['shows'];


?>

<div>

    <div class="movies-grid">
        <?php foreach ($shows as $show): ?>
            <?php component('movie-list/movie', ['show' => $show]) ?>
        <?php endforeach; ?>
    </div>
</div>