<div class="admin-page">
    <div class="admin-header">
        <div class="admin-header__titles">
            <p class="admin-eyebrow">Panel</p>
            <h1>Zarządzanie</h1>
        </div>
    </div>
    <div class="admin-page-content">
        <form method="GET" action="/admin" class="admin-form">
            <?php
            $options = [
                'show' => 'Filmy i seriale',
                'category' => 'Kategorie',
                'person' => 'Osoby',
                'streaming' => 'Serwisy streamingowe'
            ];
            $selectedValue = $_GET['table'] ?? null;

            render_component('select', [
                'name' => 'table',
                'options' => $options,
                'value' => $selectedValue
            ]);
            ?>
            <button type="submit" class="button">Wybierz</button>
        </form>

        <?php if (isset($table_content)): ?>
            <div class="admin-table-container">
                <?php echo $table_content; ?>
            </div>
        <?php endif; ?>
    </div>
</div>