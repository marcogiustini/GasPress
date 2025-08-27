<?php
/**
 * Plugin Name: Woocommerce Groups Orders for Buddypress
 * Description: Ordini collettivi nei gruppi BuddyPress con wallet condivisi, shop filtrati e gestione logistica.
 * Version: 1.0
 * Author: Marco Giustini
 */

defined('ABSPATH') || exit;

foreach (glob(plugin_dir_path(__FILE__) . 'includes/*.php') as $file) {
    require_once $file;
}

function wgo_load_textdomain() {
    load_plugin_textdomain('wgo', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'wgo_load_textdomain');

function wgo_enqueue_styles() {
    wp_enqueue_style('wgo-style', plugin_dir_url(__FILE__) . 'assets/css/wgo-style.css');
}
add_action('wp_enqueue_scripts', 'wgo_enqueue_styles');
