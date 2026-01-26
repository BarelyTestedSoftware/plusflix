<?php
$router = $params['router'];
$streaming = $params['streaming'] ?? null;
$isEdit = $streaming !== null;
$header = $isEdit ? 'Edytuj platformę' : 'Dodaj platformę';
$nameValue = $streaming ? $streaming->getName() : '';
$logoSrcValue = $streaming && $streaming->getLogoImage() ? $streaming->getLogoImage()->getSrc() : '';
$logoAltValue = $streaming && $streaming->getLogoImage() ? $streaming->getLogoImage()->getAlt() : '';
?>

<div class="admin-page">
    <div class="admin-header">
        <a href="/admin/streaming" class="admin-back-link" aria-label="Wróć do listy platform">
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
                <label for="name" class="form-label">Nazwa platformy</label>
                <?php component('input-field', [
                    'name' => 'name',
                    'id' => 'name',
                    'placeholder' => 'Wpisz nazwę platformy',
                    'value' => $nameValue,
                    'required' => true,
                ]); ?>
            </div>

            <div class="form-group">
                <label for="logo_src" class="form-label">URL logo</label>
                <?php component('input-field', [
                    'name' => 'logo_image[src]',
                    'id' => 'logo_src',
                    'placeholder' => 'https://example.com/logo.png',
                    'value' => $logoSrcValue,
                ]); ?>
            </div>

            <div class="form-group">
                <label for="logo_alt" class="form-label">Tekst alternatywny logo</label>
                <?php component('input-field', [
                    'name' => 'logo_image[alt]',
                    'id' => 'logo_alt',
                    'placeholder' => 'Logo platformy',
                    'value' => $logoAltValue,
                ]); ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i>
                    <?= $isEdit ? 'Zapisz zmiany' : 'Dodaj platformę' ?>
                </button>
                <a href="/admin/streaming" class="btn btn-ghost">Anuluj</a>
            </div>
        </form>
    </div>
</div>