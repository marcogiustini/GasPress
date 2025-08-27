<?php
/**
 * Badge “📦 Ritirato” accanto al nome utente nel gruppo
 */

function wgo_show_pickup_badge($user_id, $group_id) {
    // Recupera l'ordine attivo del gruppo
    $order = wgo_get_active_order_for_group($group_id);
    if (!$order) return;

    // Verifica se l'utente ha confermato il ritiro
    $confirmed = get_post_meta($order->ID, 'pickup_confirmed_' . $user_id, true);
    if ($confirmed) {
        echo '<span class="wgo-pickup-badge">📦 Ritirato</span>';
    }
}

// Esempio di utilizzo nel loop dei membri del gruppo
// echo esc_html($member->display_name);
// wgo_show_pickup_badge($member->ID, $group_id);
