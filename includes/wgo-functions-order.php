<?php
function wgo_aggregate_group_order($order_id) {
    $users = get_post_meta($order_id);
    $totals = [];

    foreach ($users as $key => $value) {
        if (strpos($key, 'user_') === 0 && strpos($key, '_quantities') !== false) {
            foreach ($value as $product_id => $qty) {
                if (!isset($totals[$product_id])) {
                    $totals[$product_id] = 0;
                }
                $totals[$product_id] += intval($qty);
            }
        }
    }

    return $totals;
}
