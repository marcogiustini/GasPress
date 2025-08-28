<?php
/**
 * Metabox per assegnare un prodotto a un gruppo BuddyPress
 */

defined('ABSPATH') || exit;

// 🧩 Aggiunge il metabox nel backend prodotto
function wpgas_add_group_metabox() {
    add_meta_box(
        'wpgas_group_metabox',
        esc_html__('Gruppo BuddyPress', 'wpgas'),
        'wpgas_render_group_metabox',
        'product',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'wpgas_add_group_metabox');

// 🖼️ Render del metabox
function wpgas_render_group_metabox($post) {
    $selected_group = get_post_meta($post->ID, 'wpgas_group_id', true);
    $groups = groups_get_groups(['show_hidden' => true]);

    wp_nonce_field('wpgas_save_group_meta', 'wpgas_group_meta_nonce');

    echo '<label for="wpgas_group_id">' . esc_html__('Seleziona gruppo:', 'wpgas') . '</label><br>';
    echo '<select name="wpgas_group_id" id="wpgas_group_id">';
    echo '<option value="">' . esc_html__('Nessun gruppo', 'wpgas') . '</option>';

    foreach ($groups['groups'] as $group) {
        $selected = selected($selected_group, $group->id, false);
        echo '<option value="' . esc_attr($group->id) . '" ' . esc_attr($selected) . '>' . esc_html($group->name) . '</option>';
    }

    echo '</select>';
}

// 💾 Salvataggio sicuro del metadato
function wpgas_save_product_group_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $nonce = isset($_POST['wpgas_group_meta_nonce']) ? sanitize_text_field(wp_unslash($_POST['wpgas_group_meta_nonce'])) : '';
    if (!$nonce || !wp_verify_nonce($nonce, 'wpgas_save_group_meta')) {
        return;
    }

    $group_id = isset($_POST['wpgas_group_id']) ? sanitize_text_field(wp_unslash($_POST['wpgas_group_id'])) : '';
    update_post_meta($post_id, 'wpgas_group_id', $group_id);
}
add_action('save_post', 'wpgas_save_product_group_meta');
