<?php
/**
 * Template email riepilogativa per venditori Dokan
 */

defined('ABSPATH') || exit;

function wgo_send_vendor_summary_email($vendor_id) {
    $orders = wgo_get_group_orders_for_vendor($vendor_id);
    if (empty($orders)) return;

    $vendor = get_userdata($vendor_id);
    if (!$vendor || !is_email($vendor->user_email)) return;

    $message = "Ciao {$vendor->display_name},\n\n";
    $message .= "Ecco il riepilogo degli ordini collettivi assegnati a te:\n\n";

    foreach ($orders as $order_post) {
        $order = wc_get_order($order_post->ID);
        $pickup = get_post_meta($order->get_id(), 'wgo_pickup_point', true);
        $group_id = get_post_meta($order->get_id(), 'wgo_group_id', true);
        $items = wgo_group_items_by_vendor($order)[$vendor_id] ?? [];

        $message .= "🛒 Ordine #" . esc_html($order->get_id()) . " (Gruppo #" . esc_html($group_id) . ")\n";
        $message .= "📍 Punto di ritiro: " . esc_html($pickup) . "\n";
        $message .= "📦 Prodotti:\n";

        foreach ($items as $item) {
            $name = isset($item['name']) ? sanitize_text_field($item['name']) : '';
            $qty = isset($item['qty']) ? intval($item['qty']) : 0;
            $price = isset($item['price']) ? floatval($item['price']) : 0.00;

            $message .= "- {$name} × {$qty} (€" . number_format($price, 2) . ")\n";
        }

        $message .= "\n";
    }

    $message .= "Grazie per la collaborazione!\nIl team Mercato Solidale";

    wp_mail(
        $vendor->user_email,
        __('Riepilogo ordini collettivi ricevuti', 'WP-GAS-main'),
        $message
    );
}
