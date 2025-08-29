<?php
defined('ABSPATH') || exit;

function wgo_render_vendor_dashboard_tab() {
    echo '<h2>' . esc_html__('Ordini collettivi ricevuti', 'gaspress-main') . '</h2>';
    // Logica per visualizzare gli ordini
}

function wgo_get_vendor_group_orders($vendor_id) {
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
