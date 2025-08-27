<?php
/**
 * Plugin Name: Woocommerce Groups Orders for Buddypress
 * Plugin URI: https://example.com/
 * Description: Ordini collettivi nei gruppi BuddyPress con wallet condivisi, shop filtrati, logistica e conferma ritiro.
 * Version: 1.0
 * Author: Marco Giustini
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wgo
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

// 🔁 Carica tutti i moduli dalla cartella includes/
foreach (glob(plugin_dir_path(__FILE__) . 'includes/*.php') as $file) {
    require_once $file;
}

// 🌍 Localizzazione
function wgo_load_textdomain() {
    load_plugin_textdomain('wgo', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'wgo_load_textdomain');

// 🎨 CSS + JS frontend
function wgo_enqueue_assets() {
    wp_enqueue_style('wgo-style', plugin_dir_url(__FILE__) . 'assets/css/wgo-style.css');
    wp_enqueue_script('wgo-script', plugin_dir_url(__FILE__) . 'assets/js/wgo-script.js', [], false, true);
}
add_action('wp_enqueue_scripts', 'wgo_enqueue_assets');

// 🧪 Compatibilità minima
function wgo_check_dependencies() {
    if (!function_exists('wc_get_order') || !function_exists('bp_get_group_id')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('Questo plugin richiede WooCommerce e BuddyPress attivi.');
    }
}
register_activation_hook(__FILE__, 'wgo_check_dependencies');
