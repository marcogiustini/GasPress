<?php
function wgo_render_order_participation_form($group_id) {
    $order = wgo_get_active_order_for_group($group_id);
    if (!$order) return;

    $products = get_post_meta($order->ID, 'products', true);
    echo '<form method="post"><h4>Partecipa all’ordine</h4>';
    foreach ($products as $product_id => $qty) {
        $product = wc_get_product($product_id);
        echo '<label>' . $product->get_name() . '</label>';
        echo '<input type="number" name="wgo_qty[' . $product_id . ']" min="0" value="0" /><br>';
    }
    echo '<input type="submit" name="wgo_submit_order" value="Partecipa" />';
    echo '</form>';

    if (isset($_POST['wgo_submit_order']) && isset($_POST['wgo_qty'])) {
        $user_id = get_current_user_id();
        update_post_meta($order->ID, 'user_' . $user_id . '_quantities', $_POST['wgo_qty']);
        wgo_notify_user($user_id, 'Hai partecipato all’ordine collettivo.');
        echo '<p>Partecipazione registrata.</p>';
    }
}
add_action('bp_after_group_home_content', function () {
    if (bp_is_group()) {
        $group_id = bp_get_group_id();
        wgo_render_order_participation_form($group_id);
    }
});
