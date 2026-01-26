<?php
$router = $params['router'];
$person = $params['person'] ?? null;
$isEdit = $person !== null;
$header = $isEdit ? 'Edytuj osobę' : 'Dodaj osobę';
$nameValue = $person ? $person->getName() : '';
$typeValue = $person ? $person->getType() : '';
?>

<div class="admin-page">
	<div class="admin-header">
		<a href="/admin/person" class="admin-back-link" aria-label="Wróć do listy osób">
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
				<label for="name" class="form-label">Imię i nazwisko</label>
				<?php component('input-field', [
					'name' => 'name',
					'id' => 'name',
					'placeholder' => 'Wpisz imię i nazwisko',
					'value' => $nameValue,
					'required' => true,
				]); ?>
			</div>

			<div class="form-group">
				<label for="type" class="form-label">Typ</label>
				<?php component('select', [
					'name' => 'type',
					'id' => 'type',
					'options' => [0 => 'Aktor', 1 => 'Reżyser'],
					'value' => $typeValue,
					'placeholder' => 'Wybierz typ',
					'required' => true,
				]); ?>
			</div>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary">
					<i class="fa-solid fa-check"></i>
					<?= $isEdit ? 'Zapisz zmiany' : 'Dodaj osobę' ?>
				</button>
				<a href="/admin/person" class="btn btn-ghost">Anuluj</a>
			</div>
		</form>
	</div>
</div>
