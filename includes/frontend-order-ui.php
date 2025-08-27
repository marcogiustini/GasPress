<?php
/**
 * UI frontend per partecipazione all’ordine collettivo
 */

function wgo_render_order_ui($group_id, $order_id) {
    if (!is_user_logged_in()) return;

    $user_id = get_current_user_id();
    $pickup = wgo_get_group_pickup_point($group_id);
    $wallet_balance = wgo_get_wallet_balance('user', $user_id);

    echo '<div class="wgo-order-ui">';
    echo '<h3>Partecipa all’Ordine Collettivo</h3>';

    // Punto di ritiro
    echo '<div class="wgo-pickup-point">';
    echo '<strong>Punto di Ritiro:</strong> ' . esc_html($pickup);
    echo '</div>';

    // Form di partecipazione
    echo '<form method="post">';
    echo '<label for="product_qty">Quantità desiderata:</label><br>';
    echo '<input type="number" name="product_qty" min="1" value="1" required><br><br>';

    echo '<label for="fund_amount">Versa fondi (€):</label><br>';
    echo '<input type="number" name="fund_amount" min="0" step="0.01" required><br><br>';

    echo '<p>Saldo wallet disponibile: <strong>€' . number_format($wallet_balance, 2) . '</strong></p>';

    echo '<input type="submit" name="submit_order_participation" class="button button-primary" value="Partecipa">';
    echo '</form>';

    // Gestione invio
    if (isset($_POST['submit_order_participation'])) {
        $qty = intval($_POST['product_qty']);
        $amount = floatval($_POST['fund_amount']);

        if ($amount > $wallet_balance) {
            echo '<p style="color:red;">Saldo insufficiente nel tuo wallet.</p>';
        } else {
            // Salva partecipazione (es. come post meta o custom DB)
            update_post_meta($order_id, 'user_' . $user_id . '_qty', $qty);
            update_post_meta($order_id, 'user_' . $user_id . '_funds', $amount);

            // Trasferisci fondi al wallet del gruppo
            wgo_transfer_funds('user', $user_id, 'group', $group_id, $amount);

            echo '<p style="color:green;">Partecipazione registrata con successo!</p>';
        }
    }

    echo '</div>';
}
