<?php
/**
 * Storico ritiri per un ordine collettivo
 */

// 🔍 Mostra lo storico dei ritiri per un ordine specifico
function wgo_render_pickup_history($order_id) {
    $group_id = get_post_meta($order_id, 'group_id', true);
    if (!$group_id) return;

    $members = groups_get_group_members(['group_id' => $group_id]);
    if (empty($members['members'])) return;

    echo '<div class="wgo-pickup-history">';
    echo '<h4>Storico ritiri ordine #' . esc_html($order_id) . '</h4>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>Utente</th><th>Stato</th><th>Data ritiro</th></tr></thead><tbody>';

    foreach ($members['members'] as $member) {
        $user_id = $member->ID;
        $user = get_userdata($user_id);
        $confirmed = get_post_meta($order_id, 'pickup_confirmed_' . $user_id, true);

        echo '<tr>';
        echo '<td>' . esc_html($user->display_name) . '</td>';
        if ($confirmed) {
            echo '<td style="color:green;">Ritirato</td>';
            echo '<td>' . esc_html($confirmed) . '</td>';
        } else {
            echo '<td style="color:red;">Non ritirato</td>';
            echo '<td>—</td>';
        }
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}
