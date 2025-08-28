<?php
/**
 * Impostazione punto di ritiro globale per ordini collettivi
 */

defined('ABSPATH') || exit;

// 🧩 Aggiunge il campo nel backend WooCommerce
add_action('woocommerce_product_options_general_product_data', function () {
    woocommerce_wp_text_input([
        'id'          => 'wgo_global_pickup',
        'label'       => esc_html__('Punto di ritiro globale', 'wpgas'),
        'desc_tip'    => true,
        'description' => esc_html__('Specificare un punto di ritiro valido per tutti gli ordini di questo prodotto.', 'wpgas'),
    ]);
    wp_nonce_field('wgo_save_global_pickup', 'wgo_global_pickup_nonce');
});

// 💾 Salvataggio sicuro del metadato
add_action('woocommerce_process_product_meta', function ($post_id) {
    $nonce = isset($_POST['wgo_global_pickup_nonce']) ? sanitize_text_field(wp_unslash($_POST['wgo_global_pickup_nonce'])) : '';
    if (!$nonce || !wp_verify_nonce($nonce, 'wgo_save_global_pickup')) {
        return;
    }

    if (isset($_POST['wgo_global_pickup'])) {
        $pickup = sanitize_text_field(wp_unslash($_POST['wgo_global_pickup']));
        update_post_meta($post_id, 'wgo_global_pickup', $pickup);
    }
});
