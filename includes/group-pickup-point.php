<?php
/**
 * Punto di ritiro per singolo gruppo BuddyPress
 */

// Salva il punto di ritiro per un gruppo
function wgo_set_group_pickup_point($group_id, $address) {
    update_group_meta($group_id, 'wgo_group_pickup_point', sanitize_text_field($address));
}

// Recupera il punto di ritiro del gruppo
function wgo_get_group_pickup_point($group_id) {
    return get_group_meta($group_id, 'wgo_group_pickup_point', true);
}

// UI per l’amministratore del gruppo
function wgo_render_group_pickup_form() {
    if (!bp_is_group() || !groups_is_user_admin(bp_loggedin_user_id(), bp_get_group_id())) return;

    $group_id = bp_get_group_id();
    $current = wgo_get_group_pickup_point($group_id);

    echo '<h4>' . __('Punto di Ritiro del Gruppo', 'wgo') . '</h4>';
    echo '<form method="post">';
    echo '<input type="text" name="wgo_group_pickup" value="' . esc_attr($current) . '" style="width: 400px;">';
    echo '<p class="description">' . __('Questo indirizzo sarà usato per la spedizione degli ordini collettivi del gruppo.', 'wgo') . '</p>';
    echo '<p><input type="submit" name="wgo_save_group_pickup" class="button button-primary" value="' . __('Salva', 'wgo') . '"></p>';
    echo '</form>';

    if (isset($_POST['wgo_save_group_pickup'])) {
        wgo_set_group_pickup_point($group_id, $_POST['wgo_group_pickup']);
        echo '<div class="updated"><p>' . __('Punto di ritiro del gruppo aggiornato.', 'wgo') . '</p></div>';
    }
}
add_action('bp_after_group_home_content', 'wgo_render_group_pickup_form');

// Visualizzazione per i membri del gruppo
function wgo_show_group_pickup_point() {
    if (!bp_is_group()) return;

    $group_id = bp_get_group_id();
    $pickup = wgo_get_group_pickup_point($group_id);

    if ($pickup) {
        echo '<h4>' . __('Punto di Ritiro', 'wgo') . '</h4>';
        echo '<p>' . esc_html($pickup) . '</p>';
    }
}
add_action('bp_after_group_home_content', 'wgo_show_group_pickup_point');
