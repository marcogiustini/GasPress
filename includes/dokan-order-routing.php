<?php
/**
 * Raggruppa i prodotti di un ordine WooCommerce per vendor Dokan
 */

function wgo_group_items_by_vendor($wc_order) {
    $vendor_map = [];

    foreach ($wc_order->get_items() as $item) {
        $product = $item->get_product();
        if (!$product) continue;

        $vendor_id = get_post_field('post_author', $product->get_id());
        if (!isset($vendor_map[$vendor_id])) {
            $vendor_map[$vendor_id] = [];
        }

        $vendor_map[$vendor_id][] = [
            'name'  => $product->get_name(),
            'qty'   => $item->get_quantity(),
            'price' => $item->get_total(),
        ];
    }

    return $vendor_map;
}
