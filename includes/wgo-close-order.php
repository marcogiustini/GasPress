<?php
function wgo_close_group_order($group_order_id) {
    $group_id = get_post_meta($group_order_id, 'group_id', true);
    $products = get_post_meta($group_order_id, 'products', true);
    $pickup_point = wgo_get_group_pickup_point($group_id);

    $order = wc_create_order();
    foreach ($products as $product_id => $qty) {
        $order->add_product(wc_get_product($product_id), $qty);
    }

    $order->calculate_totals();
    $order->update_status('processing');
    update_post_meta($order->get_id(), 'wgo_pickup_point', $pickup_point);
    update_post_meta($order->get_id(), 'wgo_vendor_ids', wgo_extract_vendor_ids($products));

    wgo_notify_dokan_vendors($order, $group_id, $pickup_point);
}
