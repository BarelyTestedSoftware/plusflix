<?php
    $table_column_names = $params['table_column_names'] ?? [];
    $data = $params['data'] ?? [];
    $id = $data['id'] ?? null;
    $values = array_values($data);

    if (!function_exists('format_value_modal')) {
        function format_value_modal( $value ) {
            if ( is_null( $value ) ) {
                return '-';
            }
            if ( is_array($value) && ($value['src'] ?? false) ) {
                return '<img src="' . e( $value['src'] ) . '" alt="' . e( $value['alt'] ?? '' ) . '">';
            }
            if ( is_array($value) && ($value['name'] ?? false) ) {
                return e( $value['name']);
            }
            if (is_array($value)) {
                $html = '<div class="admin-modal__pills">';
                foreach ($value as $item) {
                    $html .= '<span class="admin-modal__pill">' . e($item["name"] ?? $item) . '</span>';
                }
                $html .= '</div>';
                return $html;
            }
            return '<p class="admin-modal__value">' . e($value) . '</p>';
        }
    }
?>

<div class="admin-modal" id="admin-modal-<?= e($id); ?>" style="display: none;">
    <div class="admin-modal__content">
        <div class="admin-modal__header">
            <p>Szczegóły</p>
            <div class ="admin-modal__close-icon" onclick="closeModal(<?= e($id); ?>)">
                <i class="fa-solid fa-x fa-lg"></i>
            </div>
        </div>
        <div class="admin-modal__body">
            <?php foreach ($table_column_names as $index => $label): ?>
                <?php 
                    $value = $values[$index] ?? null;
                    
                    if ($value === null) {
                        continue;
                    }
                ?>
                <div class="admin-modal__element">
                    <p class="admin-modal__label"><?= e($label) ?></p>
                    <?= format_value_modal( $value ); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
