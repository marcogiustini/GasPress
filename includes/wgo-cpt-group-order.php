<?php
add_action('init', 'wgo_register_group_order_cpt');

function wgo_register_group_order_cpt() {
    register_post_type('group_order', [
        'label' => 'Ordini di Gruppo',
        'public' => false,
        'show_ui' => true,
        'supports' => ['title', 'custom-fields'],
        'menu_icon' => 'dashicons-groups',
        'capability_type' => 'post',
        'capabilities' => [
            'create_posts' => 'manage_options',
        ],
    ]);
}
