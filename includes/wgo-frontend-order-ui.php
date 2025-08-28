<?php
defined('ABSPATH') || exit;

function wgo_render_order_ui($group_id) {
    $products = wgo_get_group_products($group_id);
    if (empty($products)) {
        echo '<p>' . esc_html__('Nessun prodotto disponibile per questo gruppo.', 'wp-gas-main') . '</p>';
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

    echo '<button type="submit">' . esc_html__('Partecipa all’ordine collettivo', 'wp-gas-main') . '</button>';
    echo '</form>';
}

function wgo_handle_order_submission() {
    $method = sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD'] ?? ''));
    if (strtoupper($method) !== 'POST') return;

    $nonce = sanitize_text_field(wp_unslash($_POST['wgo_order_nonce'] ?? ''));
    if (!$nonce || !wp_verify_nonce($nonce, 'wgo_submit_order')) return;

    $selected_products = array_map('intval', (array) wp_unslash($_POST['wgo_selected_products'] ?? []));
    $qty_raw = wp_unslash($_POST['wgo_qty'] ?? []);
    $quantities = [];

    foreach ($qty_raw as $product_id => $qty) {
        $product_id = intval($product_id);
        $qty = intval($qty);
        if ($product_id > 0 && $qty > 0) {
            $quantities[$product_id] = $qty;
        }
    }

    // Salvataggio ordine collettivo
}
add_action('init', 'wgo_handle_order_submission');
