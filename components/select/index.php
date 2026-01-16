<?php
/**
 * Select Component
 * Wygląd jak select-with-search, ale bez wyszukiwania
 */

$name = $params['name'] ?? 'select';
$options = $params['options'] ?? [];
$placeholder = $params['placeholder'] ?? 'Wybierz...';
$required = $params['required'] ?? false;
$className = $params['class'] ?? '';
$value = $params['value'] ?? '';
$onchange = $params['onchange'] ?? '';
?>
<div class="select-with-search select-no-search" data-component="select-no-search" <?= $onchange ? 'data-onchange="' . e($onchange) . '"' : '' ?>>
    <div class="select-with-search__input" tabindex="0">
        <span class="select-with-search__selected">
            <?php
            if ($value && isset($options[$value])) {
                echo e($options[$value]);
            } else {
                echo e($placeholder);
            }
            ?>
        </span>
        <span class="select-with-search__arrow"><i class="fa-solid fa-chevron-down"></i></span>
    </div>
    <ul class="select-with-search__dropdown" hidden>
        <?php foreach ($options as $optValue => $optLabel): ?>
            <li class="select-with-search__option" data-value="<?= e($optValue) ?>">
                <?= e($optLabel) ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <input <?= $required ? 'required' : '' ?> type="hidden" name="<?= e($name) ?>" value="<?= e($value) ?>">
</div>
<script>
(function() {
    document.querySelectorAll('[data-component="select-no-search"]').forEach(function(component) {
        if (component.dataset.initialized) return;
        component.dataset.initialized = 'true';
        const input = component.querySelector('.select-with-search__input');
        const dropdown = component.querySelector('.select-with-search__dropdown');
        const options = component.querySelectorAll('.select-with-search__option');
        const selectedSpan = component.querySelector('.select-with-search__selected');
        const hiddenInput = component.querySelector('input[type="hidden"]');
        let isOpen = false;

        function openDropdown() {
            dropdown.hidden = false;
            isOpen = true;
        }
        function closeDropdown() {
            dropdown.hidden = true;
            isOpen = false;
        }

        input.addEventListener('click', function() {
            isOpen ? closeDropdown() : openDropdown();
        });
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                isOpen ? closeDropdown() : openDropdown();
            } else if (e.key === 'Escape') {
                closeDropdown();
            }
        });
        document.addEventListener('click', function(e) {
            if (!component.contains(e.target)) closeDropdown();
        });
        options.forEach(function(option) {
            option.addEventListener('click', function() {
                const value = option.dataset.value;
                selectedSpan.textContent = option.textContent;
                hiddenInput.value = value;
                closeDropdown();
                
                // Wywołaj onchange jeśli zdefiniowany
                const onchangeAttr = component.dataset.onchange;
                if (onchangeAttr) {
                    const func = new Function('return ' + onchangeAttr);
                    func.call(hiddenInput);
                }
            });
        });
    });
})();
</script>
