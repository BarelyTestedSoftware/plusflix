<?php
/**
 * Widok: Szczegóły filmu (Zgodny ze screenem Stranger Things)
 */

$show = $params['show'] ?? null;

if (!$show) {
    echo "<div class='no-show-found'>Nie znaleziono produkcji.</div>";
    return;
}


$coverImage = $show->getCoverImage();
$bgImage = $show->getBackgroundImage();

$coverSrc = $coverImage ? $coverImage->getSrc() : 'https://placehold.co/600x900/1a1a1a/666666?text=Brak+Okładki';
$coverAlt = $coverImage ? $coverImage->getAlt() : 'Plakat produkcji';

$rating = $show->getRating();
$ratingValue = $rating !== null ? number_format((float) $rating, 1) : '-';
$year = substr($show->getProductionDate() ?? '', 0, 4);
$typeLabel = $show->getType() === 2 ? 'SERIAL' : 'FILM';


$directorName = 'Nieznany';
if (method_exists($show, 'getDirector') && $show->getDirector()) {
    $directorName = $show->getDirector()->getName();
}


$streamings = method_exists($show, 'getStreamings') ? $show->getStreamings() : [];

$categories = $show->getCategories();
$director = method_exists($show, 'getDirector') ? $show->getDirector() : null;
$actors = method_exists($show, 'getActors') ? $show->getActors() : [];
?>

<?php if ($bgImage): ?>
    <?php component('movie-background', ['backgroundImage' => $bgImage]); ?>
<?php endif; ?>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">

    <div class="movie-details-wrapper">
        <div class="movie-details-grid">

            <div class="movie-poster-col">
                <img src="<?= e($coverSrc) ?>"
                     alt="<?= e($coverAlt) ?>"
                     class="poster-image">
            </div>

            <div class="movie-info-col">

                <span class="badge-category"><?= $typeLabel ?></span>

                <h1 class="movie-title"><?= e($show->getTitle()) ?></h1>

                <div class="movie-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i> <?= $year ?>
                    </div>

                    <?php if ($show->getNumberOfEpisodes()): ?>
                        <div class="meta-item">
                            <i class="fas fa-tv"></i> <?= $show->getNumberOfEpisodes() ?> odcinków
                        </div>
                    <?php endif; ?>
                </div>

                <?php component('rate', [
                    'id' => $show->getId(),
                    'averageRating' => $show->getRating(),
                    'numberOfRatings' => $show->getNumberOfRatings()
                ]); ?>

                <?php if (!empty($categories)): ?>
                    <div class="movie-categories">
                        <?php foreach ($categories as $category): ?>
                            <span class="badge-genre"><?= e(strtoupper($category->getName())) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($streamings)): ?>
                    <div class="platforms-section">
                        <h3 class="section-heading-large">Gdzie obejrzeć?</h3>

                        <div class="platforms-list">
                            <?php foreach ($streamings as $streaming): ?>
                                <?php
                                $name = $streaming->name ?? '';
                                $image = $streaming->getLogoImage();
                                $imageSrc = $image->getSrc();
                                ?>

                                <div class="platform-icon" title="<?= e($name) ?>">
                                    <?php if ($imageSrc): ?>
                                        <img src="<?= e($imageSrc) ?>" alt="<?= e($name) ?>" class="platform-icon-image">
                                    <?php else: ?>
                                        <span class="platform-icon-text"><?= strtoupper(substr($name, 0, 1)) ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="movie-description">
                    <?= e($show->getDescription()) ?>
                </div>

                <div class="credits-grid">
                    <?php if (! empty($director)): ?>
                        <div>
                            <span class="section-label">Reżyser</span>
                            <div class="credit-name"><?= e($director->getName()) ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if (! empty($actors)): ?>
                        <div>
                            <span class="section-label">W rolach głównych</span>
                            <div class="credit-name"><?= e(implode(', ', array_map(fn($actor) => $actor->getName() ?? '', $actors))) ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>