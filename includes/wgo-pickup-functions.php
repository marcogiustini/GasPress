<?php
function wgo_confirm_pickup($order_id, $user_id) {
    update_post_meta($order_id, 'pickup_confirmed_' . $user_id, current_time('mysql'));
}

function wgo_show_pickup_badge($user_id, $group_id) {
    $order = wgo_get_active_order_for_group($group_id);
    if (!$order) return;
    $confirmed = get_post_meta($order->ID, 'pickup_confirmed_' . $user_id, true);
    echo $confirmed ? '📦 Ritirato' : '📌 Da ritirare';
}

function wgo_notify_pending_pickups($order_id) {
    $group_id = get_post_meta($order_id, 'group_id', true);
    $members = groups_get_group_members(['group_id' => $group_id]);
    foreach ($members['members'] as $member) {
        $user_id = $member->ID;
        $confirmed = get_post_meta($order_id, 'pickup_confirmed_' . $user_id, true);
        if (!$confirmed) {
            wp_mail(get_userdata($user_id)->user_email, 'Promemoria: ritira il tuo ordine', 'Hai ancora un ordine da ritirare.');
        }
    }
}
