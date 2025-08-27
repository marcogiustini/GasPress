function wgo_notify_pending_pickups($order_id) {
    $group_id = get_post_meta($order_id, 'group_id', true);
    $members = groups_get_group_members(['group_id' => $group_id]);

    foreach ($members['members'] as $member) {
        $user_id = $member->ID;
        $confirmed = get_post_meta($order_id, 'pickup_confirmed_' . $user_id, true);

        if (!$confirmed) {
            wp_mail(
                get_userdata($user_id)->user_email,
                'Promemoria: ritira il tuo ordine',
                'Hai ancora un ordine da ritirare presso il punto di distribuzione del gruppo.'
            );
        }
    }
}
