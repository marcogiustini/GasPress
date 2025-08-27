<?php
/**
 * Chiusura ordine collettivo con fondi reali e supporto multi-vendor
 */

function wgo_close_order($order_id) {
    $group_id = get_post_meta($order_id, 'group_id', true);
    $pickup_point = wgo_get_group_pickup_point($group_id);
    $participants = groups_get_group_members(['group_id' => $group_id])['members'];

    $items = [];
    $vendor_ids = [];
    $total_amount = 0;

    // Aggrega partecipazioni
    foreach ($participants as $member) {
        $user_id = $member->ID;
        $qty = intval(get_post_meta($order_id, 'user_' . $user_id . '_qty', true));
        $funds = floatval(get_post_meta($order_id, 'user_' . $user_id . '_funds', true));

        if ($qty > 0 && $funds > 0) {
            $product = wc_get_product_by_sku('group-product'); // oppure dinamico
            $vendor_id = get_post_field('post_author', $product->get_id());
            $vendor_ids[$vendor_id] = true;

            $items[] = [
                'product' => $product,
                'qty' => $qty,
                'price' => $funds / $qty,
                'vendor_id' => $vendor_id,
            ];

            $total_amount += $funds;
        }
    }

    // Verifica fondi nel wallet del gruppo
    $group_balance = wgo_get_wallet_balance('group', $group_id);
    if ($group_balance < $total_amount) {
        return new WP_Error('insufficient_funds', __('Fondi insufficienti nel wallet del gruppo.', 'wgo'));
    }

    // Crea ordine WooCommerce unico
    $wc_order = wc_create_order();

    foreach ($items as $item) {
        $wc_order->add_product($item['product'], $item['qty'], [
            'subtotal' => $item['price'] * $item['qty'],
            'total' => $item['price'] * $item['qty'],
        ]);
    }

    $wc_order->set_address(['address_1' => $pickup_point], 'shipping');
    $wc_order->update_meta_data('group_id', $group_id);
    $wc_order->update_meta_data('wgo_pickup_point', $pickup_point);
    $wc_order->update_meta_data('wgo_order_type', 'multi-vendor-unified');
    $wc_order->update_meta_data('wgo_vendor_ids', array_keys($vendor_ids));
    $wc_order->calculate_totals();
    $wc_order->update_status('processing');
    $wc_order->save();

    // Deduzione fondi dal wallet del gruppo
    wgo_deduct_wallet('group', $group_id, $total_amount);

    // Notifica personalizzata ai vendor
    if (function_exists('wgo_notify_dokan_vendors')) {
        wgo_notify_dokan_vendors($wc_order, $group_id, $pickup_point);
    }

    // Salva metadati
    update_post_meta($order_id, 'wgo_wc_order_id', $wc_order->get_id());
    update_post_meta($order_id, 'wgo_closed', current_time('mysql'));

    return $wc_order->get_id();
}
