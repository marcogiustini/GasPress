<?php
/**
 * Notifiche personalizzate ai vendor Dokan alla chiusura di un ordine collettivo
 */

//  Raggruppa prodotti per vendor
function wgo_group_items_by_vendor($wc_order) {
    $vendor_map = [];

    foreach ($wc_order->get_items() as $item) {
        $product = $item->get_product();
        if (!$product) continue;

        $vendor_id = get_post_field('post_author', $product->get_id());
        if (!isset($vendor_map[$vendor_id])) {
            $vendor_map[$vendor_id] = [];
        }

        $vendor_map[$vendor_id][] = [
            'name' => $product->get_name(),
            'qty' => $item->get_quantity(),
            'price' => $item->get_total(),
        ];
    }

    return $vendor_map;
}

//  Invia email personalizzata a ciascun vendor
function wgo_notify_dokan_vendors($wc_order, $group_id, $pickup_point) {
    $vendor_map = wgo_group_items_by_vendor($wc_order);

    foreach ($vendor_map as $vendor_id => $items) {
        $vendor = get_userdata($vendor_id);
        if (!$vendor) continue;

        $vendor_name = $vendor->display_name;
        $vendor_email = $vendor->user_email;

        $lines = '';
        $total = 0;
        foreach ($items as $item) {
            $lines .= '- ' . $item['name'] . ' × ' . $item['qty'] . "\n";
            $total += $item['price'];
        }

        $message = "Ciao $vendor_name,\n\n"
                 . "Hai ricevuto un ordine collettivo dal Gruppo #$group_id.\n"
                 . "Spedisci i seguenti prodotti al punto di ritiro:\n"
                 . "$pickup_point\n\n"
                 . "Prodotti assegnati:\n$lines\n"
                 . "Totale da ricevere: €" . number_format($total, 2);

        wp_mail($vendor_email, 'Ordine collettivo da Gruppo #' . $group_id, $message);

        //  (Facoltativo) trasferimento fondi al wallet del vendor
        if (function_exists('wgo_transfer_funds')) {
            wgo_transfer_funds('group', $group_id, 'vendor', $vendor_id, $total);
        }
    }
}
