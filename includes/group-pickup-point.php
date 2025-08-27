function wgo_set_group_pickup_point($group_id, $address) {
    update_group_meta($group_id, 'wgo_group_pickup_point', sanitize_text_field($address));
}
function wgo_get_group_pickup_point($group_id) {
    return get_group_meta($group_id, 'wgo_group_pickup_point', true);
}
