<?php
add_action('woocommerce_payment_complete', function ($order_id) {
    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();
    $amount = $order->get_total();

    $current = wgo_get_wallet_balance('user', $user_id);
    update_user_meta($user_id, 'wallet_balance', $current + $amount);
});
