<?php
function wgo_get_wallet_balance($type, $id) {
    if ($type === 'user') {
        return floatval(get_user_meta($id, 'wallet_balance', true));
    }
    if ($type === 'group') {
        return floatval(get_groupmeta($id, 'wallet_balance'));
    }
    if ($type === 'vendor') {
        return floatval(get_user_meta($id, 'vendor_wallet_balance', true));
    }
    return 0;
}

function wgo_transfer_funds($from_type, $from_id, $to_type, $to_id, $amount) {
    $from_balance = wgo_get_wallet_balance($from_type, $from_id);
    if ($from_balance < $amount) return false;

    wgo_deduct_wallet($from_type, $from_id, $amount);
    wgo_add_wallet($to_type, $to_id, $amount);
    return true;
}

function wgo_deduct_wallet($type, $id, $amount) {
    $balance = wgo_get_wallet_balance($type, $id);
    $new = max(0, $balance - $amount);
    if ($type === 'user') update_user_meta($id, 'wallet_balance', $new);
    if ($type === 'group') update_groupmeta($id, 'wallet_balance', $new);
    if ($type === 'vendor') update_user_meta($id, 'vendor_wallet_balance', $new);
}

function wgo_add_wallet($type, $id, $amount) {
    $balance = wgo_get_wallet_balance($type, $id);
    $new = $balance + $amount;
    if ($type === 'user') update_user_meta($id, 'wallet_balance', $new);
    if ($type === 'group') update_groupmeta($id, 'wallet_balance', $new);
    if ($type === 'vendor') update_user_meta($id, 'vendor_wallet_balance', $new);
}
