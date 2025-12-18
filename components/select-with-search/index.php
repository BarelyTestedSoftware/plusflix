<?php
/**
 * SelectWithSearch Component
 */

$name = $params['name'] ?? 'select';
$options = $params['options'] ?? [];
$placeholder = $params['placeholder'] ?? 'Wybierz lub wyszukaj...';
$allowCustom = $params['allowCustom'] ?? true;
$required = $params['required'] ?? false;

// Normalizuj opcje do formatu [value => label]
$normalizedOptions = [];
foreach ($options as $key => $val) {
    if (is_int($key)) {
        $normalizedOptions[$val] = $val;
    } else {
        $normalizedOptions[$key] = $val;
    }
}
?>

<div class="select-with-search" data-component="select-with-search">
    <input 
        type="text" 
        id="<?= e($name) ?>"
        name="<?= e($name) ?>"
        placeholder="<?= e($placeholder) ?>"
        class="select-with-search__input"
        autocomplete="off"
        <?= $required ? 'required' : '' ?>
        data-allow-custom="<?= $allowCustom ? 'true' : 'false' ?>"
    >
    <ul class="select-with-search__dropdown" hidden>
        <?php foreach ($normalizedOptions as $optValue => $optLabel): ?>
            <li 
                class="select-with-search__option" 
                data-value="<?= e($optValue) ?>"
            >
                <?= e($optLabel) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<script>
(function() {
    document.querySelectorAll('[data-component="select-with-search"]').forEach(function(component) {
        if (component.dataset.initialized) return;
        component.dataset.initialized = 'true';
        
        const input = component.querySelector('.select-with-search__input');
        const dropdown = component.querySelector('.select-with-search__dropdown');
        const options = component.querySelectorAll('.select-with-search__option');
        const allowCustom = input.dataset.allowCustom === 'true';
        
        let activeIndex = -1;
        
        // Pokaż dropdown przy focusie
        input.addEventListener('focus', function() {
            dropdown.hidden = false;
            filterOptions();
        });
        
        // Ukryj dropdown przy kliknięciu poza
        document.addEventListener('click', function(e) {
            if (!component.contains(e.target)) {
                dropdown.hidden = true;
                activeIndex = -1;
            }
        });
        
        // Filtruj opcje przy wpisywaniu
        input.addEventListener('input', function() {
            dropdown.hidden = false;
            filterOptions();
        });
        
        // Nawigacja klawiaturą
        input.addEventListener('keydown', function(e) {
            const visibleOptions = Array.from(options).filter(opt => !opt.hidden);
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeIndex = Math.min(activeIndex + 1, visibleOptions.length - 1);
                updateActive(visibleOptions);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = Math.max(activeIndex - 1, 0);
                updateActive(visibleOptions);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (activeIndex >= 0 && visibleOptions[activeIndex]) {
                    selectOption(visibleOptions[activeIndex]);
                }
                dropdown.hidden = true;
            } else if (e.key === 'Escape') {
                dropdown.hidden = true;
                activeIndex = -1;
            }
        });
        
        // Kliknięcie w opcję
        options.forEach(function(option) {
            option.addEventListener('click', function() {
                selectOption(option);
                dropdown.hidden = true;
            });
        });
        
        function filterOptions() {
            const search = input.value.toLowerCase();
            activeIndex = -1;
            
            options.forEach(function(option) {
                const text = option.textContent.toLowerCase();
                const matches = text.includes(search);
                option.hidden = !matches;
                option.classList.remove('select-with-search__option--active');
            });
        }
        
        function updateActive(visibleOptions) {
            options.forEach(opt => opt.classList.remove('select-with-search__option--active'));
            if (activeIndex >= 0 && visibleOptions[activeIndex]) {
                visibleOptions[activeIndex].classList.add('select-with-search__option--active');
                visibleOptions[activeIndex].scrollIntoView({ block: 'nearest' });
            }
        }
        
        function selectOption(option) {
            input.value = option.dataset.value;
            activeIndex = -1;
        }
    });
})();
</script>