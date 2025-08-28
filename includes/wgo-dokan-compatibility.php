<?php
/**
 * Compatibilità con Dokan Multi-Vendor
 */

// 🔍 Verifica se Dokan è attivo
function wgo_is_dokan_active() {
    return function_exists('dokan_get_store_url');
}

// 🔗 Ottieni URL del venditore
function wgo_get_vendor_url($vendor_id) {
    return wgo_is_dokan_active() ? dokan_get_store_url($vendor_id) : '#';
}

// 🧾 Ottieni nome del venditore
function wgo_get_vendor_name($vendor_id) {
    $user = get_userdata($vendor_id);
    return $user ? $user->display_name : __('Venditore sconosciuto', 'wgo');
}

// 🛍️ Ottieni tutti i vendor coinvolti in un ordine
function wgo_extract_vendor_ids($products) {
    $vendor_ids = [];

    foreach ($products as $product_id => $qty) {
        $vendor_id = get_post_field('post_author', $product_id);
        if ($vendor_id && !in_array($vendor_id, $vendor_ids)) {
            $vendor_ids[] = $vendor_id;
        }
    }

    return $vendor_ids;
}

// 🧩 Hook per estendere Dokan se necessario
add_action('plugins_loaded', function () {
    if (!wgo_is_dokan_active()) {
        error_log('[WGO] Dokan non rilevato. Alcune funzionalità vendor saranno disabilitate.');
    }
});
