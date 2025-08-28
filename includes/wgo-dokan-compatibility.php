<?php
/**
 * Compatibilità con Dokan per ordini collettivi
 */

defined('ABSPATH') || exit;

// 📦 Aggiunge badge "Ordine collettivo" nella dashboard Dokan
add_filter('dokan_order_number', function ($order_number, $order) {
    $group_id = get_post_meta($order->get_id(), 'wgo_group_id', true);
    if ($group_id) {
        $order_number .= ' <span class="wgo-badge wgo-badge-group">' . esc_html__('Ordine collettivo da Gruppo', 'gaspress-GAS-main') . '</span>';
    }
    return $order_number;
}, 10, 2);

// 🧩 Aggiunge colonna "Punto di ritiro" nella dashboard venditore
add_filter('dokan_order_listing_columns', function ($columns) {
    $columns['wgo_pickup'] = esc_html__('Punto di Ritiro', 'gaspress-main');
    return $columns;
});

add_action('dokan_order_listing_column_wgo_pickup', function ($order) {
    $pickup = get_post_meta($order->get_id(), 'wgo_pickup_point', true);
    echo esc_html($pickup ?: '-');
});
