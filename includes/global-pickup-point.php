function wgo_set_global_pickup_point($address) {
    update_option('wgo_global_pickup_point', sanitize_text_field($address));
}
function wgo_get_global_pickup_point() {
    return get_option('wgo_global_pickup_point');
}
