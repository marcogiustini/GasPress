<?php
add_action('add_meta_boxes', function () {
    add_meta_box('wgo_group_meta', 'Gruppo BuddyPress', 'wgo_render_group_metabox', 'product', 'side');
});

function wgo_render_group_metabox($post) {
    $group_id = get_post_meta($post->ID, 'wgo_group_id', true);
    $groups = groups_get_groups(['show_hidden' => true, 'per_page' => 100]);
    echo '<select name="wgo_group_id">';
    echo '<option value="">Nessun gruppo</option>';
    foreach ($groups['groups'] as $group) {
        $selected = selected($group_id, $group->id, false);
        echo '<option value="' . $group->id . '" ' . $selected . '>' . esc_html($group->name) . '</option>';
    }
    echo '</select>';
}

add_action('save_post_product', function ($post_id) {
    if (isset($_POST['wgo_group_id'])) {
        update_post_meta($post_id, 'wgo_group_id', intval($_POST['wgo_group_id']));
    }
});
