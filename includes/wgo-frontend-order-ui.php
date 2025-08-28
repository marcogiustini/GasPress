<?php
/**
 * Interfaccia frontend per partecipazione all’ordine collettivo
 */

defined('ABSPATH') || exit;

function wgo_render_order_ui($group_id) {
    $products = wgo_get_group_products($group_id);
    if (empty($products)) {
        echo '<p>' . esc_html__('Nessun prodotto disponibile per questo gruppo.', 'WP-GAS-main') . '</p>';
        return;
    }

    echo '<form method="post" class="wgo-order-form">';
    wp_nonce_field('wgo_submit_order', 'wgo_order_nonce');

    echo '<ul class="wgo-product-list">';
    foreach ($products as $product_id => $product_name) {
        echo '<li>';
        echo '<label>';
        echo '<input type="checkbox" name="wgo_selected_products[]" value="' . esc_attr($product_id) . '">';
        echo esc_html($product_name);
        echo '</label>';
        echo '<input type="number" name="wgo_qty[' . esc_attr($product_id) . ']" min="1" value="1">';
        echo '</li>';
    }
    echo '</ul>';

    echo '<button type="submit">' . esc_html__('Partecipa all’ordine collettivo', 'WP-GAS-main') . '</button>';
    echo '</form>';
}

function wgo_handle_order_submission() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
    if (!isset($_POST['wgo_order_nonce']) || !wp_verify_nonce($_POST['wgo_order_nonce'], 'wgo_submit_order')) return;

    $selected_products = isset($_POST['wgo_selected_products']) ? array_map('intval', (array) $_POST['wgo_selected_products']) : [];
    $quantities_raw = isset($_POST['wgo_qty']) ? $_POST['wgo_qty'] : [];

    $quantities = [];
    foreach ($quantities_raw as $product_id => $qty) {
        $product_id = intval($product_id);
        $qty = intval(wp_unslash($qty));
        if ($product_id > 0 && $qty > 0) {
            $quantities[$product_id] = $qty;
        }
    }

    // Esegui logica di salvataggio ordine collettivo
    // es: wgo_save_group_order($group_id, $selected_products, $quantities);
}
add_action('init', 'wgo_handle_order_submission');
