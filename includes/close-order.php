function wgo_close_order($order_id) {
    $group_id = get_post_meta($order_id, 'group_id', true);
    $pickup_point = wgo_get_group_pickup_point($group_id);

    $order = wc_get_order($order_id);
    $order->update_meta_data('wgo_pickup_point', $pickup_point);
    $order->save();

    $vendor_id = get_post_field('post_author', $order_id);
    wp_mail(
        get_userdata($vendor_id)->user_email,
        'Nuovo ordine collettivo da Gruppo #' . $group_id,
        'Hai ricevuto un ordine collettivo. Spedisci la merce al punto di ritiro: ' . $pickup_point
    );
}
