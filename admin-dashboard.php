function wgo_add_admin_tab($tabs) {
    $tabs['group_orders'] = [
        'name' => 'Ordini Collettivi',
        'slug' => 'group-orders',
        'position' => 30,
        'screen_function' => 'wgo_render_admin_dashboard',
    ];
    return $tabs;
}
add_filter('groups_admin_tabs', 'wgo_add_admin_tab');

function wgo_render_admin_dashboard() {
    echo '<h2>Gestione Ordini Collettivi</h2>';
}
