<?php
/**
 * Widok: Szczegóły filmu (Zgodny ze screenem Stranger Things)
 */

$show = $params['show'] ?? null;

if (!$show) {
    echo "<div style='padding: 100px; text-align: center; color: white;'>Nie znaleziono produkcji.</div>";
    return;
}


$coverImage = $show->getCoverImage();
$bgImage = $show->getBackgroundImage();

$coverSrc = $coverImage ? $coverImage->src : 'https://placehold.co/600x900/1a1a1a/666666?text=Brak+Okładki';
$coverAlt = $coverImage ? $coverImage->alt : 'Plakat produkcji';
$bgSrc = $bgImage ? $bgImage->src : null;

$rating = $show->getRating();
$ratingValue = $rating !== null ? number_format((float) $rating, 1) : '-';
$year = substr($show->getProductionDate() ?? '', 0, 4);
$typeLabel = $show->getType() === 2 ? 'SERIAL' : 'FILM';


$directorName = 'Nieznany';
if (method_exists($show, 'getDirector') && $show->getDirector()) {
    $directorName = $show->getDirector()->name;
}


$streamings = method_exists($show, 'getStreamings') ? $show->getStreamings() : [];

$categories = $show->getCategories();
$firstCategoryName = !empty($categories) ? strtoupper($categories[0]->getName()) : 'GATUNEK';
?>

<?php if ($bgSrc): ?>
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100vh; z-index: -1;">
        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(19,19,26,0.5) 0%, rgba(19,19,26,0.95) 100%); z-index: 1;"></div>
        <div style="position: absolute; inset: 0; background-color: rgba(19,19,26, 0.7); z-index: 1;"></div>
        <img src="<?= htmlspecialchars($bgSrc) ?>" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5;">
    </div>
<?php endif; ?>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">

    <div class="movie-details-wrapper">
        <div class="movie-details-grid">

            <div class="movie-poster-col">
                <img src="<?= htmlspecialchars($coverSrc) ?>"
                     alt="<?= htmlspecialchars($coverAlt) ?>"
                     class="poster-image">
            </div>

            <div class="movie-info-col">

                <span class="badge-category" style="margin-bottom: 10px;"><?= $typeLabel ?></span>

                <h1 class="movie-title" style="margin-top: 0;"><?= htmlspecialchars($show->getTitle()) ?></h1>

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

                <div class="movie-actions-row" style="margin-bottom: 40px;">

                    <span style="
                        background: rgba(108, 93, 211, 0.2);
                        color: #6C5DD3;
                        padding: 10px 20px;
                        border-radius: 8px;
                        font-weight: 700;
                        font-size: 14px;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                    ">
                        <?= htmlspecialchars($firstCategoryName) ?>
                    </span>

                    <a href="/rate?id=<?= $show->getId() ?>" style="
                        background: transparent;
                        color: #ccc;
                        padding: 10px 25px;
                        border-radius: 20px;
                        font-weight: 600;
                        text-decoration: none;
                        border: 1px solid #444;
                        transition: 0.3s;
                        font-size: 15px;
                    " onmouseover="this.style.borderColor='#fff'; this.style.color='#fff'" onmouseout="this.style.borderColor='#444'; this.style.color='#ccc'">
                        Dodaj ocenę
                    </a>

                </div>

                <?php if (!empty($streamings)): ?>
                    <div class="platforms-section">
                        <h3 style="color: white; font-size: 18px; margin-bottom: 15px; font-weight: 700;">Gdzie obejrzeć?</h3>

                        <div class="platforms-list">
                            <?php foreach ($streamings as $streaming): ?>
                                <?php
                                $name = $streaming->name ?? '';
                                $firstLetter = strtoupper(substr($name, 0, 1));

                                $brandClass = '';
                                $sName = strtolower($name);
                                if (strpos($sName, 'netflix') !== false) $brandClass = 'brand-netflix';
                                elseif (strpos($sName, 'hbo') !== false) $brandClass = 'brand-hbo';
                                elseif (strpos($sName, 'prime') !== false) $brandClass = 'brand-prime';
                                elseif (strpos($sName, 'apple') !== false) $brandClass = 'brand-apple';
                                ?>

                                <div class="platform-icon <?= $brandClass ?>" title="<?= htmlspecialchars($name) ?>">
                                    <?= $firstLetter ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="movie-description" style="margin-top: 30px;">
                    <?= htmlspecialchars($show->getDescription()) ?>
                </div>

            </div>
        </div>
    </div>
</div>