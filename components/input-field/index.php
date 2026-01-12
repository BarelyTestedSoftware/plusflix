<?php
/**
 * InputField Component
 * Uniwersalny komponent pola formularza
 */

$name = $params['name'] ?? 'input';
$id = $params['id'] ?? $name;
$type = $params['type'] ?? 'text';
$placeholder = $params['placeholder'] ?? '';
$value = $params['value'] ?? '';
$required = $params['required'] ?? false;
$disabled = $params['disabled'] ?? false;
$readonly = $params['readonly'] ?? false;
$maxlength = $params['maxlength'] ?? null;
$minlength = $params['minlength'] ?? null;
$pattern = $params['pattern'] ?? null;
$className = $params['class'] ?? '';
?>

<div class="input-field-wrapper">
<input 
    type="<?= e($type) ?>"
    id="<?= e($id) ?>"
    name="<?= e($name) ?>"
    value="<?= e($value) ?>"
    placeholder="<?= e($placeholder) ?>"
    class="input-field <?= e($className) ?>"
    <?= $required ? 'required' : '' ?>
    <?= $disabled ? 'disabled' : '' ?>
    <?= $readonly ? 'readonly' : '' ?>
    <?= $maxlength !== null ? 'maxlength="' . e($maxlength) . '"' : '' ?>
    <?= $minlength !== null ? 'minlength="' . e($minlength) . '"' : '' ?>
    <?= $pattern !== null ? 'pattern="' . e($pattern) . '"' : '' ?>
>
</div>
