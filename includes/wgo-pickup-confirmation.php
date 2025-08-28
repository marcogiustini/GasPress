<?php
/**
 * Conferma ritiro ordine da parte dell’utente
 */

defined('ABSPATH') || exit;

function wpgas_render_pickup_confirmation_button($order_id) {
    if (!is_user_logged_in()) {
        return;
    }

    $user_id = get_current_user_id();
    $confirmed = get_post_meta($order_id, 'wpgas_pickup_confirmed_' . $user_id, true);

    if ($confirmed) {
        echo '<p class="wpgas-confirmed">' . esc_html__('Hai già confermato il ritiro.', 'wpgas') . '</p>';
        return;
    }

    echo '<form method="post" class="wpgas-confirm-form">';
    wp_nonce_field('wpgas_confirm_pickup_' . $order_id, 'wpgas_pickup_nonce');
    echo '<input type="hidden" name="wpgas_order_id" value="' . esc_attr($order_id) . '">';
    echo '<button type="submit">' . esc_html__('Conferma ritiro', 'wpgas') . '</button>';
    echo '</form>';
}

function wpgas_handle_pickup_confirmation() {
    $method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : '';
    if ($method !== 'POST') {
        return;
    }

    $nonce = isset($_POST['wpgas_pickup_nonce']) ? sanitize_text_field(wp_unslash($_POST['wpgas_pickup_nonce'])) : '';
    $order_id = isset($_POST['wpgas_order_id']) ? intval(wp_unslash($_POST['wpgas_order_id'])) : 0;

    if (!$nonce || !$order_id || !wp_verify_nonce($nonce, 'wpgas_confirm_pickup_' . $order_id)) {
        return;
    }

    $user_id = get_current_user_id();
    if ($user_id > 0) {
        update_post_meta($order_id, 'wpgas_pickup_confirmed_' . $user_id, current_time('mysql'));
    }
}
add_action('init', 'wpgas_handle_pickup_confirmation');
