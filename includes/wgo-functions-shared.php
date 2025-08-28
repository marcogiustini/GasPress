<?php
function wgo_group_items_by_vendor($wc_order) {
    $vendor_map = [];
    foreach ($wc_order->get_items() as $item) {
        $product = $item->get_product();
        if (!$product) continue;
        $vendor_id = get_post_field('post_author', $product->get_id());
        $vendor_map[$vendor_id][] = [
            'name' => $product->get_name(),
            'qty' => $item->get_quantity(),
            'price' => $item->get_total(),
        ];
    }
    return $vendor_map;
}

function wgo_get_active_order_for_group($group_id) {
    $orders = get_posts([
        'post_type' => 'group_order',
        'meta_query' => [[
            'key' => 'group_id',
            'value' => $group_id,
            'compare' => '='
        ]]
    ]);
    return !empty($orders) ? $orders[0] : false;
}
