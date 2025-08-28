<?php
/**
 * Funzioni condivise per WP GAS
 */

defined('ABSPATH') || exit;

/**
 * Restituisce i prodotti assegnati a un gruppo BuddyPress
 *
 * @param int $group_id
 * @return array
 */
function wgo_get_group_products($group_id) {
    if (!is_numeric($group_id) || $group_id <= 0) {
        return [];
    }

    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => [
            [
                'key'     => 'wgo_group_id',
                'value'   => intval($group_id),
                'compare' => '=',
                'type'    => 'NUMERIC',
            ],
        ],
    ];

    $query = new WP_Query($args);
    return $query->posts;
}
