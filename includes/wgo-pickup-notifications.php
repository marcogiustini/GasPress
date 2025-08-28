<?php
/**
 * Promemoria automatici per ritiro ordini collettivi
 */

// 🔔 Invia promemoria agli utenti che non hanno ancora ritirato
function wgo_notify_pending_pickups($order_id) {
    $group_id = get_post_meta($order_id, 'group_id', true);
    if (!$group_id) return;

    $members = groups_get_group_members(['group_id' => $group_id]);
    if (empty($members['members'])) return;

    foreach ($members['members'] as $member) {
        $user_id = $member->ID;
        $confirmed = get_post_meta($order_id, 'pickup_confirmed_' . $user_id, true);

        if (!$confirmed) {
            $user = get_userdata($user_id);
            if (!$user) continue;

            $message = "Ciao {$user->display_name},\n\n"
                     . "Hai ancora un ordine da ritirare presso il punto di distribuzione del gruppo.\n"
                     . "Ti invitiamo a completare il ritiro il prima possibile.";

            wp_mail($user->user_email, 'Promemoria: ritira il tuo ordine', $message);
            wgo_notify_user($user_id, 'Hai un ordine da ritirare.');
        }
    }
}

// 🕒 Hook per invocare il promemoria manualmente o via cron
add_action('wgo_check_pending_pickups', function () {
    $orders = get_posts([
        'post_type' => 'group_order',
        'post_status' => 'publish',
        'numberposts' => -1
    ]);

    foreach ($orders as $order) {
        wgo_notify_pending_pickups($order->ID);
    }
});
