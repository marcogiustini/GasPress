<?php
/**
 * Dashboard venditore Dokan con ordini collettivi
 */

defined('ABSPATH') || exit;

function wgo_render_vendor_dashboard($vendor_id) {
    $orders = wgo_get_group_orders_for_vendor($vendor_id);
    if (empty($orders)) {
        echo '<p>' . esc_html__('Nessun ordine collettivo disponibile.', 'WP-GAS-main') . '</p>';
        return;
    }

    echo '<table class="wgo-vendor-table">';
    echo '<thead><tr>';
    echo '<th>' . esc_html__('Ordine', 'WP-GAS-main') . '</th>';
    echo '<th>' . esc_html__('Punto di ritiro', 'WP-GAS-main') . '</th>';
    echo '<th>' . esc_html__('Prodotti', 'WP-GAS-main') . '</th>';
    echo '</tr></thead>';
    echo '<tbody>';

    foreach ($orders as $order_post) {
        $order = wc_get_order($order_post->ID);
        if (!$order) continue;

        $pickup = get_post_meta($order->get_id(), 'wgo_pickup_point', true);
        $items = wgo_group_items_by_vendor($order)[$vendor_id] ?? [];

        echo '<tr>';
        echo '<td>#' . esc_html($order->get_id()) . '</td>';
        echo '<td>' . esc_html($pickup ?: '-') . '</td>';
        echo '<td><ul>';
        foreach ($items as $item) {
            $name = isset($item['name']) ? sanitize_text_field($item['name']) : '';
            $qty = isset($item['qty']) ? intval($item['qty']) : 0;
            echo '<li>' . esc_html($name) . ' × ' . esc_html($qty) . '</li>';
        }
        echo '</ul></td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
}
