<?php 
    $table_column_names = $params['table_column_names'] ?? [];
    $data = $params['data'] ?? [];
    $no_data_message = $params['no_data_message'] ?? 'No data available.';
    $editUrl = $params['edit_url'] ?? '/edit';
    $deleteUrl = $params['delete_url'] ?? '/delete';
    $router = $params['router'];

    $n_columns = count( $table_column_names );

    function format_value( $value ) {
        if ( is_null( $value ) ) {
            return '-';
        }
        if ( $value['src'] ?? false ) {
            return '<img src="' . e( $value['src'] ) . '" alt="' . e( $value['alt'] ?? '' ) . '">';
        }
        if ( is_array( $value ) ) {
            return count( $value );
        }

        return mb_strlen( $value ) > 50 ? mb_substr( $value, 0, 50 ) . '...' : $value;
    }
?>

<div class="admin-table__wrapper">
<table class="admin-table">
    <thead>
        <tr>
            <?php foreach ( $table_column_names as $column_name ) : ?>
                <th scope="col" class="manage-column">
                    <?= e( $column_name ); ?>
                </th>

            <?php endforeach; ?>
            <th colspan="2"></th>
        </tr>
    </thead>
    <tbody>
        <?php if ( empty( $data ) ) : ?>
            <tr>
                <td colspan="<?= $n_columns; ?>" class="no-data">
                    <?= e( $no_data_message ); ?>
                </td>
            </tr>
        <?php else : ?>
            <?php foreach ( $data as $row ) : ?>
                <tr onclick="openModal(<?= e( $row['id'] ?? '' ); ?>)">
                    <?php foreach ( $row as $item ) : ?>
                        <td>
                            <?= format_value( $item ); ?>
                        </td>
                    <?php endforeach; ?>
                    <td class="icon" onclick="edit(event, <?= e( $row['id'] ?? '' ); ?>)"><i class="fa-solid fa-pen-to-square"></i></td>
                    <td class="icon" onclick="del(event, <?= e( $row['id'] ?? '' ); ?>)"><i class="fa-solid fa-trash"></i></td>
                </tr>
                <?= component('admin-modal', [
                    'table_column_names' => $table_column_names,
                    'data' => $row,
                ]); ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
</div>

<script>
    const ADMIN_TABLE_URL = <?= json_encode($router->getUri()) ?>;

    function openModal(id) {
        const modal = document.getElementById('admin-modal-' + id);
        modal.style.display = 'flex';
    }

    function closeModal(id) {
        const modal = document.getElementById('admin-modal-' + id);
        modal.style.display = 'none';
    }

    function edit(e, id) {
        e.stopPropagation();
        window.location.href = "/" + ADMIN_TABLE_URL + '/edit?id=' + id;
    }

    function del(e, id) {
        e.stopPropagation();
        window.location.href = "/" + ADMIN_TABLE_URL + '/delete?id=' + id;
    }
</script>
