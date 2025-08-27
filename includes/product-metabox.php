<?php
/**
 * Metabox per associare un prodotto WooCommerce a un gruppo BuddyPress
 */

// Aggiunge il metabox nel backend prodotto
add_action('add_meta_boxes', function () {
    add_meta_box('wgo_group_meta', __('Gruppo BuddyPress', 'wgo'), 'wgo_render_group_metabox', 'product', 'side');
});

// Render del metabox
function wgo_render_group_metabox($post) {
    $group_id = get_post_meta($post->ID, 'wgo_group_id', true);
    $groups = function_exists('groups_get_groups') ? groups_get_groups(['show_hidden' => true, 'per_page' => 100]) : ['groups' => []];

    echo '<label for="wgo_group_id">' . __('Seleziona un gruppo:', 'wgo') . '</label><br>';
    echo '<select name="wgo_group_id" id="wgo_group_id">';
    echo '<option value="">' . __('Nessun gruppo', 'wgo') . '</option>';
    foreach ($groups['groups'] as $group) {
        $selected = selected($group_id, $group->id, false);
        echo '<option value="' . esc_attr($group->id) . '" ' . $selected . '>' . esc_html($group->name) . '</option>';
    }
    echo '</select>';
}

// Salva il metadato quando il prodotto viene aggiornato
add_action('save_post_product', function ($post_id) {
    if (isset($_POST['wgo_group_id'])) {
        update_post_meta($post_id, 'wgo_group_id', intval($_POST['wgo_group_id']));
    }
});
