<?php

/** @var \App\Model\Show[] $shows */
$shows = $params['shows'];
$sectionTitle = $params['sectionTitle'] ?? 'Najwyżej oceniane';
$multiline = $params['multiline'] ?? false;

?>

<div>
    <div class="category-title"><?= e($sectionTitle) ?></div>
    <div class="movies-grid<?= $multiline ? ' movies-grid--multiline' : '' ?>">
        <?php foreach ($shows as $show): ?>
            <?php component('movie-list/movie', ['show' => $show]) ?>
        <?php endforeach; ?>
    </div>
</div>