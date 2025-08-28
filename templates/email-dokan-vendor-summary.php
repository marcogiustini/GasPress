<?php
/**
 * Email riepilogativa per venditori Dokan sugli ordini collettivi ricevuti
 */

// 📬 Invia email riepilogativa a un singolo venditore
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

        $message .= "🛒 Ordine #" . $order->get_id() . " (Gruppo #" . $group_id . ")\n";
        $message .= "📍 Punto di ritiro: " . $pickup . "\n";
        $message .= "📦 Prodotti:\n";

        foreach ($items as $item) {
            $message .= "- " . $item['name'] . " × " . $item['qty'] . " (€" . number_format($item['price'], 2) . ")\n";
        }

        $message .= "\n";
    }

    $message .= "Grazie per la collaborazione!\nIl team Mercato Sociale";

    wp_mail($vendor->user_email, 'Riepilogo ordini collettivi ricevuti', $message);
}

// 🔁 Invia email a tutti i venditori coinvolti
function wgo_send_all_vendor_summaries() {
    $orders = get_posts([
        'post_type' => 'shop_order',
        'post_status' => 'any',
        'numberposts' => -1,
        'meta_query' => [[
            'key' => 'wgo_vendor_ids',
            'compare' => 'EXISTS'
        ]]
    ]);

    $vendors = [];

    foreach ($orders as $order_post) {
        $vendor_ids = get_post_meta($order_post->ID, 'wgo_vendor_ids', true);
        if (is_array($vendor_ids)) {
            foreach ($vendor_ids as $vendor_id) {
                $vendors[$vendor_id] = true;
            }
        }
    }

    foreach (array_keys($vendors) as $vendor_id) {
        wgo_send_vendor_summary_email($vendor_id);
    }
}
