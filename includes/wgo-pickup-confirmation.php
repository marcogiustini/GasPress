<?php
/**
 * Conferma ritiro ordine da parte dell’utente
 */

defined('ABSPATH') || exit;

function wgo_render_pickup_confirmation_button($order_id) {
    if (!is_user_logged_in()) return;

    $user_id = get_current_user_id();
    $confirmed = get_post_meta($order_id, 'wgo_pickup_confirmed_' . $user_id, true);

    if ($confirmed) {
        echo '<p class="wgo-confirmed">' . esc_html__('Hai già confermato il ritiro.', 'WP-GAS-main') . '</p>';
        return;
    }

    echo '<form method="post" class="wgo-confirm-form">';
    wp_nonce_field('wgo_confirm_pickup_' . $order_id, 'wgo_pickup_nonce');
    echo '<input type="hidden" name="wgo_order_id" value="' . esc_attr($order_id) . '">';
    echo '<button type="submit">' . esc_html__('Conferma ritiro', 'WP-GAS-main') . '</button>';
    echo '</form>';
}

function wgo_handle_pickup_confirmation() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
    if (
        !isset($_POST['wgo_pickup_nonce']) ||
        !isset($_POST['wgo_order_id']) ||
        !wp_verify_nonce($_POST['wgo_pickup_nonce'], 'wgo_confirm_pickup_' . intval($_POST['wgo_order_id']))
    ) {
        return;
    }

    $order_id = intval(wp_unslash($_POST['wgo_order_id']));
    $user_id = get_current_user_id();

    if ($order_id > 0 && $user_id > 0) {
        update_post_meta($order_id, 'wgo_pickup_confirmed_' . $user_id, current_time('mysql'));
    }
}
add_action('init', 'wgo_handle_pickup_confirmation');
