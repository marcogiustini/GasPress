<?php
/**
 * Conferma ritiro da parte dell’utente per un ordine collettivo
 */

// ✅ Salva conferma ritiro
function wgo_confirm_pickup($order_id, $user_id) {
    update_post_meta($order_id, 'pickup_confirmed_' . $user_id, current_time('mysql'));
    wgo_notify_user($user_id, 'Hai confermato il ritiro del tuo ordine.');
}

// 🖼️ Mostra pulsante di conferma nel gruppo
function wgo_render_pickup_confirmation_button($group_id) {
    $user_id = get_current_user_id();
    $order = wgo_get_active_order_for_group($group_id);
    if (!$order) return;

    $confirmed = get_post_meta($order->ID, 'pickup_confirmed_' . $user_id, true);
    if ($confirmed) {
        echo '<p class="wgo-confirmed">✅ Hai già confermato il ritiro il ' . esc_html($confirmed) . '</p>';
        return;
    }

    echo '<form method="post" class="wgo-confirm-form">';
    echo '<input type="submit" name="wgo_confirm_pickup" value="Conferma ritiro" class="button" />';
    echo '</form>';

    if (isset($_POST['wgo_confirm_pickup'])) {
        wgo_confirm_pickup($order->ID, $user_id);
        echo '<p class="wgo-success">Ritiro confermato con successo.</p>';
    }
}

// 📍 Inserisci il pulsante nel contenuto del gruppo
add_action('bp_after_group_home_content', function () {
    if (bp_is_group()) {
        $group_id = bp_get_group_id();
        wgo_render_pickup_confirmation_button($group_id);
    }
});
