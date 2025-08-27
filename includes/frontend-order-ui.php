function wgo_render_order_ui($group_id, $order_id) {
    $pickup = wgo_get_group_pickup_point($group_id);
    echo '<h4>Punto di Ritiro</h4>';
    echo '<p>' . esc_html($pickup) . '</p>';
}
