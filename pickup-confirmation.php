function wgo_confirm_pickup($order_id, $user_id) {
    update_post_meta($order_id, 'pickup_confirmed_' . $user_id, current_time('mysql'));
}
