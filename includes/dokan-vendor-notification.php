<?php
function wgo_notify_dokan_vendors($wc_order, $group_id, $pickup_point) {
    $vendor_map = wgo_group_items_by_vendor($wc_order);

    foreach ($vendor_map as $vendor_id => $items) {
        $vendor_name = wgo_get_dokan_vendor_name($vendor_id);
        $vendor_email = wgo_get_dokan_vendor_email($vendor_id);

        $lines = '';
        foreach ($items as $item) {
            $lines .= '- ' . $item['name'] . ' × ' . $item['qty'] . "\n";
        }

        $message = "Ciao $vendor_name,\n\n"
                 . "Hai ricevuto un ordine collettivo dal Gruppo #$group_id.\n"
                 . "Spedisci i seguenti prodotti al punto di ritiro:\n"
                 . "$pickup_point\n\n"
                 . "Prodotti assegnati:\n$lines";

        wp_mail($vendor_email, 'Ordine collettivo da Gruppo #' . $group_id, $message);
    }
}
