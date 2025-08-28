<?php
function wgo_notify_dokan_vendors($wc_order, $group_id, $pickup_point) {
    $vendor_map = wgo_group_items_by_vendor($wc_order);

    foreach ($vendor_map as $vendor_id => $items) {
        $vendor = get_userdata($vendor_id);
        if (!$vendor) continue;

        $lines = '';
        $total = 0;
        foreach ($items as $item) {
            $lines .= '- ' . $item['name'] . ' × ' . $item['qty'] . "\n";
            $total += $item['price'];
        }

        $message = "Ciao {$vendor->display_name},\n\n"
                 . "Hai ricevuto un ordine collettivo dal Gruppo #$group_id.\n"
                 . "Spedisci i prodotti al punto di ritiro:\n$pickup_point\n\n"
                 . "Prodotti assegnati:\n$lines\nTotale: €" . number_format($total, 2);

        wp_mail($vendor->user_email, 'Ordine collettivo da Gruppo #' . $group_id, $message);
    }
}
