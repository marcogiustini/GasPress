<?php
/**
 * Chiusura ordine collettivo: aggrega partecipazioni, crea ordine WooCommerce, assegna punto di ritiro
 */

function wgo_close_order($order_id) {
    $group_id = get_post_meta($order_id, 'group_id', true);
    $pickup_point = wgo_get_group_pickup_point($group_id);
    $participants = groups_get_group_members(['group_id' => $group_id])['members'];

    $items = [];
    $total_amount = 0;

    // Aggrega quantità e fondi versati da ogni utente
    foreach ($participants as $member) {
        $user_id = $member->ID;
        $qty = intval(get_post_meta($order_id, 'user_' . $user_id . '_qty', true));
        $funds = floatval(get_post_meta($order_id, 'user_' . $user_id . '_funds', true));

        if ($qty > 0 && $funds > 0) {
            $items[] = [
                'name' => 'Prodotto Gruppo',
                'qty' => $qty,
                'price' => $funds / $qty,
            ];
            $total_amount += $funds;
        }
    }

    // Crea ordine WooCommerce
    $wc_order = wc_create_order();
    foreach ($items as $item) {
        $wc_order->add_product(wc_get_product_by_sku('group-product'), $item['qty'], [
            'subtotal' => $item['price'] * $item['qty'],
            'total' => $item['price'] * $item['qty'],
        ]);
    }

    // Assegna metadati
    $wc_order->set_address([
        'address_1' => $pickup_point,
    ], 'shipping');

    $wc_order->update_meta_data('group_id', $group_id);
    $wc_order->update_meta_data('wgo_pickup_point', $pickup_point);
    $wc_order->update_meta_data('wgo_order_type', 'single-group');
    $wc_order->calculate_totals();
    $wc_order->update_status('processing');
    $wc_order->save();

    // Notifica vendor
    $vendor_id = get_post_field('post_author', $wc_order->get_id());
    wp_mail(
        get_userdata($vendor_id)->user_email,
        'Nuovo ordine collettivo da Gruppo #' . $group_id,
        'Hai ricevuto un ordine collettivo. Spedisci la merce al punto di ritiro: ' . $pickup_point
    );

    // Conferma chiusura
    update_post_meta($order_id, 'wgo_wc_order_id', $wc_order->get_id());
    update_post_meta($order_id, 'wgo_closed', current_time('mysql'));

    return $wc_order->get_id();
}
