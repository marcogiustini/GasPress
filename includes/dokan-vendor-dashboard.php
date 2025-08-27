<?php
/**
 * Dashboard Dokan: riepilogo ordini collettivi per vendor
 */

// 🔧 Aggiunge la voce "Ordini Collettivi" nel menu Dokan
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

// 📄 Carica il template della pagina
add_action('dokan_load_custom_template', function ($query_var) {
    if ($query_var === 'group-orders') {
        add_action('dokan_dashboard_content_inside', 'wgo_render_vendor_group_orders');
    }
});

// Rende visibile la tabella degli ordini collettivi
function wgo_render_vendor_group_orders() {
    $vendor_id = get_current_user_id();
    $status = $_GET['order_status'] ?? '';
    $date_from = $_GET['date_from'] ?? '';
    $date_to = $_GET['date_to'] ?? '';

    echo '<h2>' . __('Ordini Collettivi Assegnati', 'wgo') . '</h2>';

    // Filtro
    echo '<form method="get" style="margin-bottom:20px;">';
    echo '<input type="hidden" name="group-orders" value="1">';
    echo '<label>' . __('Stato ordine:', 'wgo') . '</label>';
    echo '<select name="order_status">
            <option value="">' . __('Tutti', 'wgo') . '</option>
            <option value="wc-processing"' . selected($status, 'wc-processing', false) . '>' . __('In lavorazione', 'wgo') . '</option>
            <option value="wc-completed"' . selected($status, 'wc-completed', false) . '>' . __('Completato', 'wgo') . '</option>
          </select>';
    echo '<label style="margin-left:20px;">' . __('Dal:', 'wgo') . '</label>';
    echo '<input type="date" name="date_from" value="' . esc_attr($date_from) . '">';
    echo '<label style="margin-left:10px;">' . __('Al:', 'wgo') . '</label>';
    echo '<input type="date" name="date_to" value="' . esc_attr($date_to) . '">';
    echo '<input type="submit" value="' . __('Filtra', 'wgo') . '" class="button" style="margin-left:20px;">';
    echo '</form>';

    // Query ordini
    $args = [
        'post_type' => 'shop_order',
        'post_status' => $status ? [$status] : ['wc-processing', 'wc-completed'],
        'meta_query' => [[
            'key' => 'wgo_vendor_ids',
            'value' => '"' . $vendor_id . '"',
            'compare' => 'LIKE',
        ]],
        'date_query' => [],
    ];
    if ($date_from) $args['date_query'][] = ['after' => $date_from];
    if ($date_to) $args['date_query'][] = ['before' => $date_to];

    $orders = get_posts($args);

    if (empty($orders)) {
        echo '<p>' . __('Nessun ordine collettivo trovato.', 'wgo') . '</p>';
        return;
    }

    echo '<table class="dokan-table">';
    echo '<thead><tr><th>#</th><th>' . __('Punto di Ritiro', 'wgo') . '</th><th>' . __('Prodotti', 'wgo') . '</th><th>' . __('Data', 'wgo') . '</th></tr></thead><tbody>';

    foreach ($orders as $order_post) {
        $order = wc_get_order($order_post->ID);
        $pickup = get_post_meta($order->get_id(), 'wgo_pickup_point', true);
        $items = function_exists('wgo_group_items_by_vendor') ? wgo_group_items_by_vendor($order)[$vendor_id] ?? [] : [];

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
