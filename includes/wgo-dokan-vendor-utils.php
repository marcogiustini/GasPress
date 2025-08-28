<?php
/**
 * Utility per venditori Dokan
 */

// 🔍 Ottieni l’ID del venditore da un prodotto
function wgo_get_vendor_id_by_product($product_id) {
    return get_post_field('post_author', $product_id);
}

// 📦 Ottieni tutti i prodotti di un venditore
function wgo_get_products_by_vendor($vendor_id) {
    $args = [
        'post_type' => 'product',
        'author'    => $vendor_id,
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];
    return get_posts($args);
}

// 📄 Ottieni tutti gli ordini assegnati a un venditore
function wgo_get_group_orders_for_vendor($vendor_id) {
    $args = [
        'post_type' => 'shop_order',
        'post_status' => 'any',
        'meta_query' => [[
            'key'     => 'wgo_vendor_ids',
            'value'   => '"' . $vendor_id . '"',
            'compare' => 'LIKE',
        ]]
    ];
    return get_posts($args);
}

// 🧾 Ottieni il totale assegnato al venditore in un ordine
function wgo_get_vendor_total_in_order($order, $vendor_id) {
    $items = wgo_group_items_by_vendor($order)[$vendor_id] ?? [];
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'];
    }
    return $total;
}

// 🔗 Ottieni URL del profilo venditore
function wgo_get_vendor_profile_url($vendor_id) {
    return function_exists('dokan_get_store_url') ? dokan_get_store_url($vendor_id) : '#';
}
