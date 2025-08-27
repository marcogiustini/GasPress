<?php
/**
 * Riepilogo ordini collettivi nel pannello Dokan del vendor
 */

// 🔧 Aggiungi voce nel menu Dokan
add_filter('dokan_query_var_filter', function ($vars) {
    $vars[] = 'group-orders';
    return $vars;
});

add_filter('dokan_get_dashboard_nav', function ($nav) {
    $nav['group-orders'] = [
        'title' => __('Ordini Collettivi', 'wgo'),
        'icon'  => 'fa fa-users',
        'url'   => dokan_get_navigation_url('group-orders'),
        'pos'   => 80,
    ];
    return $nav;
});

// 📄 Template della pagina
add_action('dokan_load_custom_template', function ($query_var) {
    if ($query_var === 'group-orders') {
        add_action('dokan_dashboard_content_inside', 'wgo_render_vendor_group_orders');
        add_action('dokan_dashboard_wrap_start', function () {
            echo '<div class="dokan-dashboard-wrap">';
        });
        add_action('dokan_dashboard_wrap_end', function () {
            echo '</div>';
        });
    }
});

// 📦 Visualizza ordini collettivi per il vendor
function wgo_render_vendor_group_orders() {
    $vendor_id = get_current_user_id();
    $args = [
        'post_type' => 'shop_order',
        'post_status' => ['wc-processing', 'wc-completed'],
        'meta_query' => [
            [
                'key' => 'wgo_vendor_ids',
                'value' => '"' . $vendor_id . '"',
                'compare' => 'LIKE',
            ],
        ],
    ];

    $orders = get_posts($args);

    echo '<h2>' . __('Ordini Collettivi Assegnati', 'wgo') . '</h2>';
    if (empty($orders)) {
        echo '<p>' . __('Nessun ordine collettivo trovato.', 'wgo') . '</p>';
        return;
    }

    echo '<table class="dokan-table">';
    echo '<thead><tr><th>#</th><th>Punto di Ritiro</th><th>Prodotti</th><th>Data</th></tr></thead><tbody>';

    foreach ($orders as $order_post) {
        $order = wc_get_order($order_post->ID);
        $pickup = get_post_meta($order->get_id(), 'wgo_pickup_point', true);
        $items = wgo_group_items_by_vendor($order)[$vendor_id] ?? [];

        echo '<tr>';
        echo '<td><a href="' . get_edit_post_link($order->get_id()) . '">#' . $order->get_id() . '</a></td>';
        echo '<td>' . esc_html($pickup) . '</td>';
        echo '<td><ul>';
        foreach ($items as $item) {
            echo '<li>' . esc_html($item['name']) . ' × ' . intval($item['qty']) . '</li>';
        }
        echo '</ul></td>';
        echo '<td>' . esc_html($order->get_date_created()->date('d/m/Y')) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
}
