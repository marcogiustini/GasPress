function wgo_set_group_pickup_point($group_id, $address) {
    update_group_meta($group_id, 'wgo_group_pickup_point', sanitize_text_field($address));
}

function wgo_get_group_pickup_point($group_id) {
    return get_group_meta($group_id, 'wgo_group_pickup_point', true);
}

function wgo_render_group_pickup_form() {
    if (!bp_is_group() || !groups_is_user_admin(bp_loggedin_user_id(), bp_get_group_id())) return;

    $group_id = bp_get_group_id();
    $current = wgo_get_group_pickup_point($group_id);

    echo '<h4>Punto di Ritiro del Gruppo</h4>';
    echo '<form method="post">';
    echo '<input type="text" name="wgo_group_pickup" value="' . esc_attr($current) . '" style="width: 400px;">';
    echo '<p><input type="submit" name="wgo_save_group_pickup" class="button button-primary" value="Salva"></p>';
    echo '</form>';

    if (isset($_POST['wgo_save_group_pickup'])) {
        wgo_set_group_pickup_point($group_id, $_POST['wgo_group_pickup']);
        echo '<div class="updated"><p>Punto di ritiro del gruppo aggiornato.</p></div>';
    }
}
add_action('bp_after_group_home_content', 'wgo_render_group_pickup_form');
