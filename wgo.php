<?php
/**
 * Plugin Name: Group Orders for BuddyPress and WooCommerce
 * Description: Gestione di ordini collettivi nei gruppi BuddyPress con wallet condivisi, punti di ritiro e compatibilità multi-vendor.
 * Version: 1.0.0
 * Author: Marco Giustini
 * Author URI: https://github.com/marcogiustini
 * Text Domain: wgo
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.5
 * Requires PHP: 7.4
 */

defined('ABSPATH') || exit;

// 🔁 Carica traduzioni
add_action('plugins_loaded', function () {
    load_plugin_textdomain('wgo', false, dirname(plugin_basename(__FILE__)) . '/languages/');
});

// 📦 Carica tutti i moduli da /includes/
foreach (glob(plugin_dir_path(__FILE__) . 'includes/wgo-*.php') as $file) {
    require_once $file;
}

// 🎨 Carica CSS e JS
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('wgo-style', plugin_dir_url(__FILE__) . 'assets/css/wgo-style.css');
    wp_enqueue_script('wgo-script', plugin_dir_url(__FILE__) . 'assets/js/wgo-script.js', [], false, true);
});

// 🔍 Verifica dipendenze minime
add_action('admin_init', function () {
    if (!function_exists('wc_get_product') || !function_exists('groups_get_group_members')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(__('Questo plugin richiede WooCommerce e BuddyPress attivi.', 'wgo'));
    }
});
