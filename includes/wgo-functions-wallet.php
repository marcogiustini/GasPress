<?php
/**
 * Funzioni per la gestione del wallet virtuale
 */

// 🔍 Ottieni saldo wallet
function wgo_get_wallet_balance($type, $id) {
    if ($type === 'group') {
        return (float) get_group_meta($id, 'wallet_balance', true);
    }
    return (float) get_user_meta($id, 'wallet_balance', true);
}

// ➕ Aggiungi fondi al wallet
function wgo_add_wallet($type, $id, $amount) {
    $current = wgo_get_wallet_balance($type, $id);
    $new = $current + floatval($amount);

    if ($type === 'group') {
        update_group_meta($id, 'wallet_balance', $new);
    } else {
        update_user_meta($id, 'wallet_balance', $new);
    }

    return $new;
}

// ➖ Rimuovi fondi dal wallet
function wgo_deduct_wallet($type, $id, $amount) {
    $current = wgo_get_wallet_balance($type, $id);
    $new = max(0, $current - floatval($amount));

    if ($type === 'group') {
        update_group_meta($id, 'wallet_balance', $new);
    } else {
        update_user_meta($id, 'wallet_balance', $new);
    }

    return $new;
}

// 🔁 Trasferisci fondi tra wallet
function wgo_transfer_wallet($from_type, $from_id, $to_type, $to_id, $amount) {
    wgo_deduct_wallet($from_type, $from_id, $amount);
    wgo_add_wallet($to_type, $to_id, $amount);
}
