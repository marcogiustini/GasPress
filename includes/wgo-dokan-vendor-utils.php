<?php
/**
 * Utility per venditori Dokan in WP GAS
 */

defined('ABSPATH') || exit;

/**
 * Restituisce gli ordini associati a un venditore Dokan
 *
 * @param int $vendor_id
 * @return array
 */
function wgo_get_vendor_orders($vendor_id) {
    if (!is_numeric($vendor_id) || $vendor_id <= 0) {
        return [];
    }

    $args = [
        'post_type'      => 'shop_order',
        'post_status'    => ['wc-completed', 'wc-processing'],
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => [
            [
                'key'     => '_dokan_vendor_id',
                'value'   => intval($vendor_id),
                'compare' => '=',
                'type'    => 'NUMERIC',
            ],
        ],
    ];

    $query = new WP_Query($args);
    return $query->posts;
}
}
