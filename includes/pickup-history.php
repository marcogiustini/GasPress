function wgo_render_pickup_history($order_id) {
    $group_id = get_post_meta($order_id, 'group_id', true);
    $members = groups_get_group_members(['group_id' => $group_id]);

    echo '<table><tr><th>Utente</th><th>Stato Ritiro</th></tr>';
    foreach ($members['members'] as $member) {
        $user_id = $member->ID;
        $confirmed = get_post_meta($order_id, 'pickup_confirmed_' . $user_id, true);

        echo '<tr><td>' . esc_html($member->display_name) . '</td><td>';
        if ($confirmed) {
            echo '<span class="wgo-pickup-badge confirmed">📦 Ritirato</span>';
        } else {
            echo '<span class="wgo-pickup-badge pending">📌 Da ritirare</span>';
        }
        echo '</td></tr>';
    }
    echo '</table>';
}
