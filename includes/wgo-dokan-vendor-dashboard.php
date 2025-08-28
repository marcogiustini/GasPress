<?php
add_filter('dokan_query_var_filter', fn($vars) => array_merge($vars, ['group-orders']));
add_filter('dokan_get_dashboard_nav', function ($nav) {
    $nav['group-orders'] = [
        'title' => 'Ordini Collettivi',
        'icon' => 'fa fa-users',
        'url' => dokan_get_navigation_url('group-orders'),
        'pos' => 80,
    ];
    return $nav;
});
add_action('dokan_load_custom_template', function ($query_var) {
    if ($query_var === 'group-orders') {
        add_action('dokan_dashboard_content_inside', 'wgo_render_vendor_group_orders');
    }
});

function wgo_render_vendor_group_orders() {
    $vendor_id = get_current_user_id();
    $args = [
        'post_type' => 'shop_order',
        'meta_query' => [[
            'key' => 'wgo_vendor_ids',
            'value' => '"' . $vendor_id . '"',
            'compare' => 'LIKE',
        ]]
    ];
    $orders = get_posts($args);
    echo '<h2>Ordini Collettivi Assegnati</h2>';
    echo '<table class="dokan-table"><thead><tr><th>#</th><th>Punto di Ritiro</th><th>Prodotti</th></tr></thead><tbody>';
    foreach ($orders as $order_post) {
        $order = wc_get_order($order_post->ID);
        $pickup = get_post_meta($order->get_id(), 'wgo_pickup_point', true);
        $items = wgo_group_items_by_vendor($order)[$vendor_id] ?? [];
        echo '<tr><td>#' . $order->get_id() . '</td><td>' . esc_html($pickup) . '</td><td><ul>';
        foreach ($items as $item) {
            echo '<li>' . esc_html($item['name']) . ' × ' . intval($item['qty']) . '</li>';
        }
        echo '</ul></td></tr>';
    }
    echo '</tbody></table>';
}
