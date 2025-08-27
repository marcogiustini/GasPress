<?php
function wgo_group_items_by_vendor($wc_order) {
    $vendor_map = [];

    foreach ($wc_order->get_items() as $item) {
        $product = $item->get_product();
        if (!$product) continue;

        $vendor_id = wgo_get_dokan_vendor_id($product->get_id());
        if (!isset($vendor_map[$vendor_id])) {
            $vendor_map[$vendor_id] = [];
        }

        $vendor_map[$vendor_id][] = [
            'name' => $product->get_name(),
            'qty' => $item->get_quantity(),
        ];
    }

    return $vendor_map;
}
