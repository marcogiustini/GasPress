<?php
function wgo_notify_user($user_id, $message, $action = 'group_order') {
    bp_notifications_add_notification([
        'user_id' => $user_id,
        'item_id' => time(),
        'component_name' => 'wgo',
        'component_action' => $action,
        'date_notified' => bp_core_current_time(),
        'is_new' => 1,
    ]);

    wp_mail(get_userdata($user_id)->user_email, 'Notifica Gruppo', $message);
}
