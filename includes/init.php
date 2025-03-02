<?php

use oniclass\Cipher;

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

add_action('init', 'oni_action_init');

/**
 * Fires after WordPress has finished loading but before any headers are sent.
 *
 */
function oni_action_init(): void
{
    oni_cookie();

    if (isset($_GET[ 'token' ]) && ! empty($_GET[ 'token' ])) {

        $Cipher = new Cipher();

        $result = $Cipher->decryptURL($_GET[ 'token' ]);

        if ($result[ 'success' ] && isset($result[ 'data' ])) {
            $mobile = $result[ 'data' ];

            $user_query = new WP_User_Query([
                'meta_key'   => 'mobile',
                'meta_value' => $mobile,
                'number'     => 1,
             ]);

            if (! empty($user_query->get_results())) {
                $user = $user_query->get_results()[ 0 ];
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);

            } else {

                $user_id = wp_create_user($mobile, wp_generate_password(), $mobile . '@example.com');

                if (! is_wp_error($user_id)) {
                    update_user_meta($user_id, 'mobile', $mobile);
                    update_user_meta($user_id, 'questions', 0);
                    update_user_meta($user_id, 'count_true', 0);
                    update_user_meta($user_id, 'count_match', 0);
                    wp_set_current_user($user_id);
                    wp_set_auth_cookie($user_id);

                }

            }

        }
        wp_redirect(home_url());

    }
}