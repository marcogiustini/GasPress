<?php
/**
 * Impostazione punto di ritiro per gruppi BuddyPress
 */

defined('ABSPATH') || exit;

// 🧩 Aggiunge il campo nel backend del gruppo BuddyPress
function wgo_render_group_pickup_field($group_id) {
    $pickup = groups_get_groupmeta($group_id, 'wgo_group_pickup');
    ?>
    <label for="wgo_group_pickup"><?php echo esc_html__('Punto di ritiro del gruppo:', 'WP-GAS-main'); ?></label><br>
    <input type="text" name="wgo_group_pickup" id="wgo_group_pickup" value="<?php echo esc_attr($pickup); ?>">
    <?php wp_nonce_field('wgo_save_group_pickup', 'wgo_group_pickup_nonce'); ?>
    <?php
}
add_action('groups_custom_group_fields_editable', 'wgo_render_group_pickup_field');

// 💾 Salvataggio sicuro del metadato
function wgo_save_group_pickup_field($group_id) {
    $nonce = isset($_POST['wgo_group_pickup_nonce']) ? sanitize_text_field(wp_unslash($_POST['wgo_group_pickup_nonce'])) : '';
    if (!$nonce || !wp_verify_nonce($nonce, 'wgo_save_group_pickup')) {
        return;
    }

    if (isset($_POST['wgo_group_pickup'])) {
        $pickup = sanitize_text_field(wp_unslash($_POST['wgo_group_pickup']));
        groups_update_groupmeta($group_id, 'wgo_group_pickup', $pickup);
    }
}
add_action('groups_custom_group_fields_updated', 'wgo_save_group_pickup_field');
