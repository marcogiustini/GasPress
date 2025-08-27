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
