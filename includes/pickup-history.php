<?php
/**
 * Storico dei ritiri per ogni ordine collettivo
 */

function wgo_render_pickup_history($order_id) {
    $group_id = get_post_meta($order_id, 'group_id', true);
    if (!$group_id) return;

    $members = groups_get_group_members(['group_id' => $group_id]);
    if (empty($members['members'])) return;

    echo '<h3>Storico Ritiri</h3>';
    echo '<table class="group-pickup-table">';
    echo '<tr><th>Utente</th><th>Stato Ritiro</th><th>Data</th></tr>';

    foreach ($members['members'] as $member) {
        $user_id = $member->ID;
        $confirmed = get_post_meta($order_id, 'pickup_confirmed_' . $user_id, true);

        echo '<tr>';
        echo '<td>' . esc_html($member->display_name) . '</td>';

        if ($confirmed) {
            echo '<td><span class="wgo-pickup-badge confirmed">📦 Ritirato</span></td>';
            echo '<td>' . esc_html(date('d/m/Y H:i', strtotime($confirmed))) . '</td>';
        } else {
            echo '<td><span class="wgo-pickup-badge pending">📌 Da ritirare</span></td>';
            echo '<td>—</td>';
        }

        echo '</tr>';
    }

    echo '</table>';
}
