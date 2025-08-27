function wgo_create_group_order($group_id, $products, $start, $end) {
    return wp_insert_post([
        'post_type' => 'group_order',
        'post_title' => 'Ordine Gruppo ' . $group_id,
        'post_status' => 'publish',
        'meta_input' => [
            'group_id' => $group_id,
            'products' => $products,
            'start_date' => $start,
            'end_date' => $end,
        ]
    ]);
}
