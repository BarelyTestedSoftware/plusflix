<?php
/**
 * SelectWithSearch Component
 */

$name = $params['name'] ?? 'select';
$options = $params['options'] ?? [];
$placeholder = $params['placeholder'] ?? 'Wybierz lub wyszukaj...';
$allowCustom = $params['allowCustom'] ?? true;
$required = $params['required'] ?? false;
$multiple = $params['multiple'] ?? true;
$selected = $params['selected'] ?? [];  // Lista ID do pre-zaznaczenia (lub pojedyncza wartość dla single)

// Konwertuj selected na array jeśli to string (dla multi-select)
if ($multiple && !is_array($selected)) {
    $selected = [$selected];
}
// Wyodrębnij pre-zaznaczone wartości i usuń je z dostępnych opcji (tylko dla multi-select)
$selectedOptions = [];
$selectedValue = '';
$selectedLabel = '';

if ($multiple) {
    if (!is_array($selected)) {
        $selected = [$selected];
    }
    foreach ($selected as $selectedId) {
        if (isset($options[$selectedId])) {
            $selectedOptions[$selectedId] = $options[$selectedId];
            unset($options[$selectedId]);
        }
    }
} else {
    // Single-select mode
    if (!empty($selected)) {
        $selectedValue = is_array($selected) ? reset($selected) : $selected;
        $selectedLabel = $options[$selectedValue] ?? $selectedValue;
    }
}
?>

<div class="select-with-search" data-component="select-with-search" data-multiselect="<?= $multiple ? 'true' : 'false' ?>">
    <input 
        type="text" 
        id="<?= e($name) ?>"
        placeholder="<?= e($placeholder) ?>"
        class="select-with-search__input"
        autocomplete="off"
        <?= $required ? 'required' : '' ?>
        data-allow-custom="<?= $allowCustom ? 'true' : 'false' ?>"
        value="<?= !$multiple ? e($selectedLabel) : '' ?>"
    >
    <ul class="select-with-search__dropdown" hidden>
        <?php foreach ($options as $optValue => $optLabel): ?>
            <li 
                class="select-with-search__option" 
                data-value="<?= e($optValue) ?>"
            >
                <?= e($optLabel) ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php if ($multiple): ?>
        <div class="select-with-search__pills">
            <?php foreach ($selectedOptions as $id => $label): ?>
                <span class="select-with-search__pill">
                    <?= e($label) ?>
                    <button class="select-with-search__remove" type="button" data-value="<?= e($id) ?>">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </span>
            <?php endforeach; ?>
        </div>
        <?php foreach ($selectedOptions as $id => $label): ?>
            <input type="hidden" name="<?= e(str_replace('[]', '', $name)) ?>[]" value="<?= e($id) ?>">
        <?php endforeach; ?>
    <?php else: ?>
        <input type="hidden" name="<?= e($name) ?>" value="<?= e($selectedValue) ?>">
    <?php endif; ?>
</div>

<script>
(function() {
    document.querySelectorAll('[data-component="select-with-search"]').forEach(function(component) {
        if (component.dataset.initialized) return;
        component.dataset.initialized = 'true';
        const input = component.querySelector('.select-with-search__input');
        const dropdown = component.querySelector('.select-with-search__dropdown');
        const options = component.querySelectorAll('.select-with-search__option');
        const pillsContainer = component.querySelector('.select-with-search__pills');
        const allowCustom = input.dataset.allowCustom === 'true';
        const isMulti = component.dataset.multiselect === 'true';
        const hiddenInput = component.querySelector('input[type="hidden"]');
        
        // Pobierz name z pierwszego hidden inputu lub z input
        let name = hiddenInput?.name || input.name;
        
        let activeIndex = -1;
        // Pobierz już zaznaczone wartości z hidden inputów
        let selected = Array.from(component.querySelectorAll('input[type="hidden"]')).map(inp => inp.value);

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
                
                // Dla single-select z allowCustom, zapisz wartość jeśli coś wpisano
                if (!isMulti && allowCustom && input.value.trim()) {
                    hiddenInput.value = input.value.trim();
                }
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
                    if (!isMulti) {
                        dropdown.hidden = true;
                    }
                } else if (allowCustom && input.value.trim()) {
                    if (isMulti) {
                        addPill(input.value.trim(), input.value.trim());
                        input.value = '';
                    } else {
                        // Single-select custom value
                        hiddenInput.value = input.value.trim();
                    }
                    dropdown.hidden = true;
                }
                activeIndex = -1;
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

        // Kliknięcie na przycisk usuwania pillu (tylko dla multi-select)
        if (isMulti && pillsContainer) {
            pillsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.select-with-search__remove')) {
                    e.preventDefault();
                    const btn = e.target.closest('.select-with-search__remove');
                    const value = btn.dataset.value;
                    removePill(value);
                }
            });
        }

        function filterOptions() {
            const search = input.value.toLowerCase();
            activeIndex = -1;
            options.forEach(function(option) {
                const text = option.textContent.toLowerCase();
                const matches = text.includes(search);
                option.hidden = !matches || selected.includes(option.dataset.value);
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
            if (isMulti) {
                if (!selected.includes(option.dataset.value)) {
                    addPill(option.dataset.value, option.textContent.trim());
                }
                input.value = '';
            } else {
                // Single-select mode
                input.value = option.textContent.trim();
                hiddenInput.value = option.dataset.value;
            }
            activeIndex = -1;
            filterOptions();
        }

        function addPill(value, label) {
            selected.push(value);
            renderPills();
        }

        function removePill(value) {
            selected = selected.filter(v => v !== value);
            renderPills();
            filterOptions();
        }

        function renderPills() {
            pillsContainer.innerHTML = '';
            selected.forEach(function(value) {
                const label = getLabel(value);
                const pill = document.createElement('span');
                pill.className = 'select-with-search__pill';
                pill.textContent = label;
                const removeBtn = document.createElement('button');
                removeBtn.className = 'select-with-search__remove';
                removeBtn.type = 'button';
                removeBtn.setAttribute('data-value', value);
                removeBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                pill.appendChild(removeBtn);
                pillsContainer.appendChild(pill);
            });
            // Hidden inputs for form submit
            component.querySelectorAll('input[type="hidden"]').forEach(i => i.remove());
            selected.forEach(function(value) {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = name;
                hidden.value = value;
                component.appendChild(hidden);
            });
        }

        function getLabel(value) {
            const opt = Array.from(options).find(o => o.dataset.value == value);
            return opt ? opt.textContent.trim() : value;
        }
    });
})();
</script>