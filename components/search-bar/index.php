<?php
$q = $_GET['q'] ?? '';
$f = $_GET['f'] ?? '';
?>

<div class="search-bar">
    <input class="<?= $f !== '' ? 'autofocused' : '' ?>" <?= $f !== '' ? 'autofocus' . ' onfocus="this.value=\'' . e($q) . '\'; goToSearch()"' : '' ?> onfocus="goToSearch()" <?= !$f ? 'value="' . e($q) . '"' : '' ?> oninput="debounceSearch(event)" onblur="submitSearch(event)" type="text" name="q" placeholder="Szukaj filmów, seriali..." autocomplete="off">
    <button type="submit" class="search-btn">
        <i class="fas fa-search"></i>
    </button>
</div>

<script>
    // Usuń klasę autofocused po załadowaniu, aby przywrócić animacje
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.querySelector('.search-bar input.autofocused');
        if (input) {
            // Usuń klasę po krótkim opóźnieniu (pozwól na zaaplikowanie stylów)
            setTimeout(() => {
                input.classList.remove('autofocused');
            }, 50);
        }
    });

    let debounceTimer;
    const DEBOUNCE_DELAY = 500; // ms
    
    function goToSearch() {
        // Przekieruj na stronę search (jeśli nie jesteś już tam)
        if (!window.location.pathname.includes('/search')) {
            window.location.href = '/search?f=true';
        }
    }
    
    function debounceSearch(e) {
        // Anuluj poprzedni timer
        clearTimeout(debounceTimer);
        
        // Ustaw nowy timer - wyślij po 500ms bez zmian
        debounceTimer = setTimeout(() => {
            submitSearch(e);
        }, DEBOUNCE_DELAY);
    }
    
    function submitSearch(e) {
        // Wyślij GET request
        const value = e.target.value.trim();
        const currentQ = '<?= e($q) ?>';
        
        if (value !== currentQ) {
            if (value) {
                window.location.href = '/search?q=' + encodeURIComponent(value) + '&f=true';
            } else {
                window.location.href = '/search?f=true';
            }
        }
    }
</script>