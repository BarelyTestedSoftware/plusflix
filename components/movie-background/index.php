<?php
/**
 * MovieBackground Component
 * Wyświetla tło z gradientem dla strony szczegółów filmu/serialu
 */

/** @var \App\Model\Media $backgroundImage */
$backgroundImage = $params['backgroundImage'] ?? null;

if (!$backgroundImage) {
    return;
}
?>

<div class="movie-background">
    <img src="<?= e($backgroundImage->src) ?>" 
         alt="<?= e($backgroundImage->alt) ?>" 
         class="movie-background-image">
    <div class="movie-background-gradient"></div>
</div>
