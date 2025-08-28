<?php
/**
 * Plugin Name: Woocommerce Groups Orders for Buddypress
 * Description: Ordini collettivi nei gruppi BuddyPress con wallet condivisi e supporto multi-vendor.
 * Version: 1.0
 * Author: Marco Giustini
 * Text Domain: wgo
 */

defined('ABSPATH') || exit;

foreach (glob(plugin_dir_path(__FILE__) . 'includes/wgo-*.php') as $file) {
    require_once $file;
}

add_action('plugins_loaded', function () {
    load_plugin_textdomain('wgo', false, dirname(plugin_basename(__FILE__)) . '/languages/');
});
