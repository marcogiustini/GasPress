<?php
function wgo_set_global_pickup_point($address) {
    update_option('wgo_global_pickup_point', sanitize_text_field($address));
}

function wgo_get_global_pickup_point() {
    return get_option('wgo_global_pickup_point');
}

function wgo_render_global_pickup_settings() {
    if (!current_user_can('manage_woocommerce')) return;

    echo '<div class="wrap"><h2>Punto di Ritiro Generale</h2>';
    echo '<form method="post">';
    echo '<input type="text" name="wgo_global_pickup" value="' . esc_attr(wgo_get_global_pickup_point()) . '" />';
    echo '<input type="submit" name="wgo_save_global_pickup" value="Salva" />';
    echo '</form>';

    if (isset($_POST['wgo_save_global_pickup'])) {
        wgo_set_global_pickup_point($_POST['wgo_global_pickup']);
        echo '<p>Punto di ritiro aggiornato.</p>';
    }

    echo '</div>';
}
add_action('admin_menu', function () {
    add_submenu_page('woocommerce', 'Punto di Ritiro', 'Punto di Ritiro', 'manage_woocommerce', 'wgo-pickup-settings', 'wgo_render_global_pickup_settings');
});
