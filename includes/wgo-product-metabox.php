<?php
/**
 * Metabox per assegnare un prodotto a un gruppo BuddyPress
 */

defined('ABSPATH') || exit;

// 🧩 Aggiunge il metabox nel backend prodotto
function wgo_add_group_metabox() {
    add_meta_box(
        'wgo_group_metabox',
        esc_html__('Gruppo BuddyPress', 'WP-GAS-main'),
        'wgo_render_group_metabox',
        'product',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'wgo_add_group_metabox');

// 🖼️ Render del metabox
function wgo_render_group_metabox($post) {
    $selected_group = get_post_meta($post->ID, 'wgo_group_id', true);
    $groups = groups_get_groups(['show_hidden' => true]);

    wp_nonce_field('wgo_save_group_meta', 'wgo_group_meta_nonce');

    echo '<label for="wgo_group_id">' . esc_html__('Seleziona gruppo:', 'WP-GAS-main') . '</label><br>';
    echo '<select name="wgo_group_id" id="wgo_group_id">';
    echo '<option value="">' . esc_html__('Nessun gruppo', 'WP-GAS-main') . '</option>';

    foreach ($groups['groups'] as $group) {
        $selected = selected($selected_group, $group->id, false);
        echo '<option value="' . esc_attr($group->id) . '" ' . esc_attr($selected) . '>' . esc_html($group->name) . '</option>';
    }

    echo '</select>';
}

// 💾 Salvataggio sicuro del metadato
function wgo_save_product_group_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (
        isset($_POST['wgo_group_meta_nonce']) &&
        wp_verify_nonce($_POST['wgo_group_meta_nonce'], 'wgo_save_group_meta')
    ) {
        $group_id = isset($_POST['wgo_group_id']) ? sanitize_text_field(wp_unslash($_POST['wgo_group_id'])) : '';
        update_post_meta($post_id, 'wgo_group_id', $group_id);
    }
}
add_action('save_post', 'wgo_save_product_group_meta');
