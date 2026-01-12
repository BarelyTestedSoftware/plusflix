
<?php
$router = $params['router'];
$show = $params['show'] ?? null;
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
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_description" class="form-label">Opis</label>
				<textarea name="show[description]" id="show_description" rows="4" placeholder="opis"></textarea>
			</div>
			<div class="form-group">
				<label for="show_type" class="form-label">Typ</label>
				<?php component('select', [
					'name' => 'show[type]',
					'options' => [1 => 'Film', 2 => 'Serial'],
					'placeholder' => 'Wybierz typ',
					'required' => true,
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_productionDate" class="form-label">Data produkcji</label>
				<?php component('input-field', [
					'name' => 'show[productionDate]',
					'id' => 'show_productionDate',
					'type' => 'date',
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_numberOfEpisodes" class="form-label">Liczba odcinków</label>
				<?php component('input-field', [
					'name' => 'show[numberOfEpisodes]',
					'id' => 'show_numberOfEpisodes',
					'type' => 'number',
					'min' => 1,
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_director" class="form-label">Reżyser</label>
				<?php component('select', [
					'name' => 'show[director]',
					'options' => array_reduce($params['directors'] ?? [], function($out, $dir) { $out[$dir->id] = $dir->name; return $out; }, []),
					'placeholder' => 'Wybierz reżysera',
					'required' => false,
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_actors" class="form-label">Aktorzy</label>
				<?php component('select-with-search', [
					'name' => 'show[actors][]',
					'options' => array_reduce($params['actors'] ?? [], function($out, $actor) { $out[$actor->id] = $actor->name; return $out; }, []),
					'placeholder' => 'Wybierz aktorów',
					'required' => false,
					'allowCustom' => true,
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
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_streamings" class="form-label">Platformy streamingowe</label>
				<?php component('select-with-search', [
					'name' => 'show[streamings][]',
					'options' => array_reduce($params['streamings'] ?? [], function($out, $stream) { $out[$stream->id] = $stream->name; return $out; }, []),
					'placeholder' => 'Wybierz platformy',
					'required' => false,
					'allowCustom' => false,
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_coverImage" class="form-label">Okładka (URL)</label>
				<?php component('input-field', [
					'name' => 'show[coverImage]',
					'id' => 'show_coverImage',
					'placeholder' => 'URL do okładki',
				]); ?>
			</div>
			<div class="form-group">
				<label for="show_backgroundImage" class="form-label">Tło (URL)</label>
				<?php component('input-field', [
					'name' => 'show[backgroundImage]',
					'id' => 'show_backgroundImage',
					'placeholder' => 'URL do tła',
				]); ?>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">
					<i class="fa-solid fa-check"></i>
					Dodaj produkcję
				</button>
				<a href="/admin/show" class="btn btn-ghost">Anuluj</a>
			</div>
		</form>
	</div>
</div>
