<?php
$data = $params['data'] ?? [];
$table_column_names = $params['table_column_names'];
$router = $params['router'];
$header = $params['header'] ?? 'Lista kategorii';
$total = count($data);

$records = 'wpisów';
if ($total === 1) {
	$records = 'wpis';
} elseif ($total > 1 && $total < 5) {
	$records = 'wpisy';
}
?>

<div class="admin-page">
	<div class="admin-header">
		<a href="/admin" class="admin-back-link" aria-label="Wróć do panelu admina">
			<i class="fa-solid fa-arrow-left fa-lg"></i>
		</a>
		<div class="admin-header__titles">
			<p class="admin-eyebrow">Zarządzanie</p>
			<h1><?= e($header) ?></h1>
			<p class="admin-subtitle">Łącznie: <?= e($total) ?> <?= e($records) ?></p>
		</div>
	<div class="admin-actions">
		<a href="<?= "/" . e($router->getUri()) . '/add' ?>" class="btn btn-primary-soft" aria-label="Dodaj nowy wpis" title="Dodaj">
				<i class="fa-solid fa-plus"></i>
				<span class="hide-mobile">Dodaj</span>
			</a>
		</div>
	</div>

		<?php component('admin-table', [
			'table_column_names' => $table_column_names,
			'data' => $data,
			'router' => $router,
		]); ?>
</div>



