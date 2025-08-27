<?php
/**
 * Invia notifiche personalizzate ai vendor Dokan alla chiusura di un ordine collettivo
 */

function wgo_notify_dokan_vendors($wc_order, $group_id, $pickup_point) {
    if (!function_exists('wgo_group_items_by_vendor')) return;

    $vendor_map = wgo_group_items_by_vendor($wc_order);

    foreach ($vendor_map as $vendor_id => $items) {
        $vendor = get_userdata($vendor_id);
        if (!$vendor) continue;

        $vendor_name  = $vendor->display_name;
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
    }
}
