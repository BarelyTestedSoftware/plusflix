
<?php
use App\Model\Show;

$router = $params['router'];
$show = $params['show'] ?? new Show();
$header = 'Dodaj produkcję';
?>

<div class="admin-page">
	<div class="admin-header">
		<a href="/admin/show" class="admin-back-link" aria-label="Wróć do listy produkcji">
			<i class="fa-solid fa-arrow-left fa-lg"></i>
		</a>
		<div class="admin-header__titles">
			<p class="admin-eyebrow">Zarządzanie</p>
			<h1><?= e($header) ?></h1>
		</div>
	</div>

	<div class="admin-card">
		<form method="post" enctype="multipart/form-data" class="admin-form">
			<div class="form-group">
				<label for="show_title" class="form-label">Tytuł</label>
				<?php component('input-field', [
                    'placeholder' => 'tytuł',
					'name' => 'show[title]',
					'id' => 'show_title',
					'required' => true,
					'value' => $show->getTitle(),
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_description" class="form-label">Opis</label>
				<textarea name="show[description]" id="show_description" rows="4" placeholder="opis"><?= e($show->getDescription()) ?></textarea>
			</div>
			<div class="form-group">
				<label for="show_type" class="form-label">Typ</label>
				<?php component('select', [
					'name' => 'show[type]',
					'options' => [1 => 'Film', 2 => 'Serial'],
					'placeholder' => 'Wybierz typ',
					'required' => true,
					'value' => $show->getType() ?? '',
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_production_date" class="form-label">Data produkcji</label>
				<?php component('input-field', [
					'name' => 'show[production_date]',
					'id' => 'show_production_date',
					'type' => 'date',
					'value' => $show->getProductionDate(),
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_number_of_episodes" class="form-label">Liczba odcinków</label>
				<?php component('input-field', [
					'name' => 'show[number_of_episodes]',
					'id' => 'show_number_of_episodes',
					'type' => 'number',
					'min' => 1,
					'value' => $show->getNumberOfEpisodes(),
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_director" class="form-label">Reżyser</label>
				<?php component('select-with-search', [
					'name' => 'show[director]',
					'options' => array_reduce($params['directors'] ?? [], function($out, $dir) { $out[$dir->getId()] = $dir->getName(); return $out; }, []),
					'placeholder' => 'Wybierz reżysera',
					'required' => false,
					'selected' => $show->getDirector()?->getId() ?? '',
					'allowCustom' => true,
					'multiple' => false,
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_actors" class="form-label">Aktorzy</label>
				<?php component('select-with-search', [
					'name' => 'show[actors][]',
					'options' => array_reduce($params['actors'] ?? [], function($out, $actor) { $out[$actor->getId()] = $actor->getName(); return $out; }, []),
					'placeholder' => 'Wybierz aktorów',
					'required' => false,
					'allowCustom' => true,
					'selected' => array_map(function($actor) { return $actor->getId(); }, $show->getActors()),
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_categories" class="form-label">Kategorie</label>
				<?php component('select-with-search', [
					'name' => 'show[categories][]',
					'options' => array_reduce($params['categories'] ?? [], function($out, $cat) { $out[$cat->getId()] = $cat->getName(); return $out; }, []),
					'placeholder' => 'Wybierz kategorie',
					'required' => false,
					'allowCustom' => false,
					'selected' => array_map(function($cat) { return $cat->getId(); }, $show->getCategories()),
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_streamings" class="form-label">Platformy streamingowe</label>
				<?php component('select-with-search', [
					'name' => 'show[streamings][]',
					'options' => array_reduce($params['streamings'] ?? [], function($out, $stream) { $out[$stream->getId()] = $stream->getName(); return $out; }, []),
					'placeholder' => 'Wybierz platformy',
					'required' => false,
					'allowCustom' => false,
					'selected' => array_map(function($stream) { return $stream->getId(); }, $show->getStreamings()),
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_coverImage" class="form-label">Okładka (URL)</label>
				<?php component('input-field', [
					'name' => 'show[coverImage]',
					'id' => 'show_coverImage',
					'placeholder' => 'URL do okładki',
					'value' => $show->getCoverImage()?->getSrc() ?? '',
				]); ?>
				<div class="image-preview" data-source-input="show_coverImage">
					<p class="image-preview__hint">Podgląd okładki pojawi się po wpisaniu adresu URL.</p>
					<img class="image-preview__img" alt="Podgląd okładki" loading="lazy">
				</div>
			</div>
			<div class="form-group">
				<label for="show_backgroundImage" class="form-label">Tło (URL)</label>
				<?php component('input-field', [
					'name' => 'show[backgroundImage]',
					'id' => 'show_backgroundImage',
					'placeholder' => 'URL do tła',
					'value' => $show->getBackgroundImage()?->getSrc() ?? '',
				]); ?>
				<div class="image-preview" data-source-input="show_backgroundImage">
					<p class="image-preview__hint">Podgląd tła pojawi się po wpisaniu adresu URL.</p>
					<img class="image-preview__img" alt="Podgląd tła" loading="lazy">
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">
					<i class="fa-solid fa-check"></i>
					<?php if($show->getId()): ?>Zapisz zmiany<?php else: ?>Dodaj produkcję<?php endif; ?>
				</button>
				<a href="/admin/show" class="btn btn-ghost">Anuluj</a>
			</div>
		</form>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
	const previews = document.querySelectorAll('.image-preview');

	const setupPreview = (preview) => {
		const inputId = preview.dataset.sourceInput;
		const input = document.getElementById(inputId);
		const img = preview.querySelector('.image-preview__img');
		const hint = preview.querySelector('.image-preview__hint');
		if (!input || !img || !hint) return;

		const defaultHint = hint.textContent;

		const showHint = (text, isError = false) => {
			hint.textContent = text;
			hint.style.display = 'block';
			hint.classList.toggle('image-preview__hint--error', Boolean(isError));
			img.removeAttribute('src');
			img.style.display = 'none';
		};

		const updatePreview = () => {
			const url = input.value.trim();
			if (!url) {
				showHint(defaultHint);
				return;
			}

			const testImage = new Image();
			testImage.onload = () => {
				img.src = url;
				img.style.display = 'block';
				hint.style.display = 'none';
				hint.classList.remove('image-preview__hint--error');
			};
			testImage.onerror = () => {
				showHint('Nie udało się wczytać obrazu. Sprawdź adres URL.', true);
			};
			testImage.src = url;
		};

		input.addEventListener('input', updatePreview);
		input.addEventListener('change', updatePreview);
		updatePreview();
	};

	previews.forEach(setupPreview);
});
</script>
