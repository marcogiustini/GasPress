<?php
/**
 * Tab vetrina prodotti per gruppi BuddyPress
 */

defined('ABSPATH') || exit;

function wgo_render_group_shop_tab($group_id) {
    $products = wgo_get_group_products($group_id);
    if (empty($products)) {
        echo '<p>' . esc_html__('Nessun prodotto disponibile per questo gruppo.', 'WP-GAS-main') . '</p>';
        return;
    }

    echo '<div class="wgo-group-shop">';
    foreach ($products as $product_id => $price) {
        echo '<div class="wgo-product">';
        echo '<h3>' . esc_html(get_the_title($product_id)) . ' - ' . esc_html(wc_price($price)) . '</h3>';
        echo '<button data-product="' . esc_attr($product_id) . '">' . esc_html__('Aggiungi all’ordine', 'WP-GAS-main') . '</button>';
        echo '</div>';
    }
    echo '</div>';
}
