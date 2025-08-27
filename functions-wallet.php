function wgo_get_wallet_balance($type, $id) {
    return $type === 'group'
        ? (float) get_group_meta($id, 'wallet_balance', true)
        : (float) get_user_meta($id, 'wallet_balance', true);
}

function wgo_transfer_funds($from_type, $from_id, $to_type, $to_id, $amount) {
    $from_balance = wgo_get_wallet_balance($from_type, $from_id);
    $to_balance = wgo_get_wallet_balance($to_type, $to_id);

    update_user_meta($from_id, 'wallet_balance', $from_balance - $amount);
    update_user_meta($to_id, 'wallet_balance', $to_balance + $amount);
}
