<?php
require_once plugin_dir_path(__FILE__) . 'dokan-vendor-utils.php';
require_once plugin_dir_path(__FILE__) . 'dokan-order-routing.php';
require_once plugin_dir_path(__FILE__) . 'dokan-vendor-notifications.php';

// Badge "Multi-Vendor" nel backend WooCommerce
add_filter('manage_shop_order_posts_columns', function ($columns) {
    $columns['wgo_dokan'] = 'Vendor';
    return $columns;
});

add_action('manage_shop_order_posts_custom_column', function ($column, $post_id) {
    if ($column === 'wgo_dokan') {
        $vendor_ids = get_post_meta($post_id, 'wgo_vendor_ids', true);
        if (is_array($vendor_ids)) {
            $names = array_map('wgo_get_dokan_vendor_name', $vendor_ids);
            echo implode(', ', $names);
        }
    }
}, 10, 2);
