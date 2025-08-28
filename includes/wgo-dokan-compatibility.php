<?php
defined('ABSPATH') || exit;

function wgo_dokan_vendor_is_active($vendor_id) {
    $active = get_user_meta($vendor_id, 'dokan_enable_selling', true);
    return $active === 'yes';
}

function wgo_render_vendor_status_message($vendor_id) {
    if (!wgo_dokan_vendor_is_active($vendor_id)) {
        echo '<p>' . esc_html__('Il venditore non è attivo.', 'GasPress-main') . '</p>';
    } else {
        echo '<p>' . esc_html__('Venditore attivo e pronto a ricevere ordini.', 'GasPress-main') . '</p>';
    }
}
