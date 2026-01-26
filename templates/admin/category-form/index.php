<?php
$router = $params['router'];
$category = $params['category'] ?? null;
$isEdit = $category !== null;
$header = $isEdit ? 'Edytuj kategorię' : 'Dodaj kategorię';
$nameValue = $category ? $category->getName() : '';
?>

<div class="admin-page">
	<div class="admin-header">
		<a href="/admin/category" class="admin-back-link" aria-label="Wróć do listy kategorii">
			<i class="fa-solid fa-arrow-left fa-lg"></i>
		</a>
		<div class="admin-header__titles">
			<p class="admin-eyebrow">Zarządzanie</p>
			<h1><?= e($header) ?></h1>
		</div>
	</div>

	<div class="admin-card">
		<form method="POST" class="admin-form">
			<div class="form-group">
				<label for="name" class="form-label">Nazwa kategorii</label>
				<?php component('input-field', [
					'name' => 'name',
					'id' => 'name',
					'placeholder' => 'Wpisz nazwę kategorii',
					'value' => $nameValue,
					'required' => true,
				]); ?>
			</div>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary">
					<i class="fa-solid fa-check"></i>
					<?= $isEdit ? 'Zapisz zmiany' : 'Dodaj kategorię' ?>
				</button>
				<a href="/admin/category" class="btn btn-ghost">Anuluj</a>
			</div>
		</form>
	</div>
</div>