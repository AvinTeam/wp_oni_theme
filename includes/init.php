<?php

function restrict_admin_access()
{
    if (! is_user_logged_in()) {
        return;
    }

    $user             = wp_get_current_user();
    $restricted_roles = [ 'subscriber' ];

    if (array_intersect($restricted_roles, $user->roles) && ! defined('DOING_AJAX')) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_init', 'restrict_admin_access');

function disable_admin_bar_for_specific_roles($show)
{
    if (is_user_logged_in()) {
        $user             = wp_get_current_user();
        $restricted_roles = [ 'subscriber' ];

        if (array_intersect($restricted_roles, $user->roles)) {
            return false;
        }
    }

    return $show;
}

add_filter('show_admin_bar', 'disable_admin_bar_for_specific_roles');

