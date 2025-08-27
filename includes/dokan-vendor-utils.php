<?php
function wgo_get_dokan_vendor_id($product_id) {
    return get_post_field('post_author', $product_id);
}

function wgo_get_dokan_vendor_email($vendor_id) {
    return get_userdata($vendor_id)->user_email;
}

function wgo_get_dokan_vendor_name($vendor_id) {
    return get_userdata($vendor_id)->display_name;
}
