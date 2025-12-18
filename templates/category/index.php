<h1>Dodaj kategorię</h1>

<form action="/category/add" method="post" class="form">
    <div class="form-group">
        <label for="name">Nazwa kategorii</label>
        <?php component('input-text', [
            'name' => 'category[name]',
            'id' => 'name',
            'placeholder' => 'Wpisz nazwę kategorii...',
            'required' => true,
            'value' => '',
        ]); ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Zapisz</button>
        <a href="/" class="btn btn-secondary">Anuluj</a>
    </div>

    <div>
        <?php foreach ($params['categories'] as $category): ?>
            <p><?= e($category->getName()) ?> <button type="button" onclick="deleteCategory(<?= $category->getId() ?>)">Usuń</button></p>
        <?php endforeach; ?>
    </div>
</form>

<script> 
    function deleteCategory(id) {
        if (confirm('Czy na pewno chcesz usunąć tę kategorię?')) {
            document.location = '/category/delete?id=' + id;    
        }
    }
</script>
