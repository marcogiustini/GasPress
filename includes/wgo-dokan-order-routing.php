<?php
/**
 * Smistamento automatico degli ordini collettivi ai venditori Dokan
 */

// 🔁 Dopo la chiusura dell’ordine, assegna i prodotti ai venditori
function wgo_route_group_order_to_vendors($group_order_id) {
    $group_id = get_post_meta($group_order_id, 'group_id', true);
    $aggregated = wgo_aggregate_group_order($group_order_id);
    $pickup_point = wgo_get_group_pickup_point($group_id);

    $vendor_map = [];

    foreach ($aggregated as $product_id => $qty) {
        $vendor_id = get_post_field('post_author', $product_id);
        if (!isset($vendor_map[$vendor_id])) {
            $vendor_map[$vendor_id] = [];
        }
        $vendor_map[$vendor_id][$product_id] = $qty;
    }

    foreach ($vendor_map as $vendor_id => $products) {
        $order = wc_create_order(['customer_id' => $vendor_id]);

        foreach ($products as $product_id => $qty) {
            $order->add_product(wc_get_product($product_id), $qty);
        }

        $order->calculate_totals();
        $order->update_status('processing');

        update_post_meta($order->get_id(), 'wgo_pickup_point', $pickup_point);
        update_post_meta($order->get_id(), 'wgo_group_id', $group_id);
        update_post_meta($order->get_id(), 'wgo_vendor_id', $vendor_id);

        wgo_notify_dokan_vendors($order, $group_id, $pickup_point);
    }
}
