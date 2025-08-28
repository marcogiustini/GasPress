<?php
/**
 * Badge visivo per stato di ritiro dell’ordine collettivo
 */

// 📦 Mostra badge “Ritirato” o “Da ritirare” per un utente
function wgo_show_pickup_badge($user_id, $group_id) {
    $order = wgo_get_active_order_for_group($group_id);
    if (!$order) return;

    $confirmed = get_post_meta($order->ID, 'pickup_confirmed_' . $user_id, true);

    if ($confirmed) {
        echo '<span class="wgo-badge wgo-badge-confirmed">📦 Ritirato</span>';
    } else {
        echo '<span class="wgo-badge wgo-badge-pending">📌 Da ritirare</span>';
    }
}
