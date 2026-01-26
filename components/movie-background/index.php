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
    <img src="<?= e($backgroundImage->getSrc()) ?>" 
         alt="<?= e($backgroundImage->getAlt()) ?>" 
         class="movie-background-image">
    <div class="movie-background-gradient"></div>
</div>
