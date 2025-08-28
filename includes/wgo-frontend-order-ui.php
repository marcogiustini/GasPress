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
    echo '<ul class="wgo-product-list">';

    foreach ($products as $product_id => $product) {
        echo '<li>';
        echo '<label>';
        echo '<input type="checkbox" name="wgo_selected_products[]" value="' . esc_attr($product_id) . '">';
        echo esc_html($product);
        echo '</label>';
        echo '</li>';
    }

    echo '</ul>';
    echo '<button type="submit">' . esc_html__('Partecipa all’ordine collettivo', 'WP-GAS-main') . '</button>';
    echo '</form>';
}
