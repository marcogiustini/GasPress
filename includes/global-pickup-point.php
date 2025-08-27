<?php
/**
 * Punto di ritiro generale per ordini multi-gruppo
 */

// Salva il punto di ritiro globale
function wgo_set_global_pickup_point($address) {
    update_option('wgo_global_pickup_point', sanitize_text_field($address));
}

// Recupera il punto di ritiro globale
function wgo_get_global_pickup_point() {
    return get_option('wgo_global_pickup_point');
}

// UI nel backend per impostare il punto di ritiro
function wgo_render_global_pickup_settings() {
    if (!current_user_can('manage_woocommerce')) return;

    echo '<div class="wrap"><h2>' . __('Punto di Ritiro Generale', 'wgo') . '</h2>';
    echo '<form method="post">';
    echo '<input type="text" name="wgo_global_pickup" value="' . esc_attr(wgo_get_global_pickup_point()) . '" style="width: 400px;">';
    echo '<p class="description">' . __('Questo punto di ritiro sarà usato per ordini collettivi multi-gruppo.', 'wgo') . '</p>';
    echo '<p><input type="submit" name="wgo_save_global_pickup" class="button button-primary" value="' . __('Salva', 'wgo') . '"></p>';
    echo '</form></div>';

    if (isset($_POST['wgo_save_global_pickup'])) {
        wgo_set_global_pickup_point($_POST['wgo_global_pickup']);
        echo '<div class="updated"><p>' . __('Punto di ritiro aggiornato.', 'wgo') . '</p></div>';
    }
}
add_action('admin_menu', function () {
    add_submenu_page(
        'woocommerce',
        __('Punto di Ritiro', 'wgo'),
        __('Punto di Ritiro', 'wgo'),
        'manage_woocommerce',
        'wgo-pickup-settings',
        'wgo_render_global_pickup_settings'
    );
});
