<?php
use oniclass\Cipher;
use oniclass\ONIDB;

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

    if (! (defined('DOING_CRON') && DOING_CRON)) {
        oni_cookie();

    }

    if (! isset($_COOKIE[ 'first_visit' ])) {
        setcookie('first_visit', '1', time() + 3600 * 24 * 3, '/');

        header('Clear-Site-Data: "cache"');
        wp_cache_flush();
    }

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
                    update_user_meta($user_id, 'score_total', 0);
                    wp_set_current_user($user_id);
                    wp_set_auth_cookie($user_id);

                }

            }

        }
        wp_redirect(home_url());

    }

    if (isset($_GET[ 'mrr_token' ]) && isset($_GET[ 'mrr_ok' ]) && ! empty($_GET[ 'mrr_token' ])) {

        $user_query = new WP_User_Query([
            'meta_key'   => 'mobile',
            'meta_value' => oni_to_enghlish($_GET[ 'mrr_token' ]),
            'number'     => 1,
         ]);

        if (! empty($user_query->get_results())) {
            $user = $user_query->get_results()[ 0 ];
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);

            wp_redirect(home_url());

        }

    }

    function disable_comments_queries($query)
    {
        if (! is_admin() && $query->is_main_query()) {
            $query->set('comments_per_page', 0); // غیرفعال کردن کامنت‌ها
        }
    }
    add_action('pre_get_posts', 'disable_comments_queries');

    function disable_author_queries($query)
    {
        if (! is_admin() && $query->is_author()) {
            $query->set_404(); // غیرفعال کردن صفحات نویسندگان
        }
    }
    add_action('pre_get_posts', 'disable_author_queries');

    function disable_category_tag_queries($query)
    {
        if (! is_admin() && ($query->is_category() || $query->is_tag())) {
            $query->set_404(); // غیرفعال کردن صفحات دسته‌بندی و تگ
        }
    }
    add_action('pre_get_posts', 'disable_category_tag_queries');

    function disable_search_queries($query)
    {
        if (! is_admin() && $query->is_search()) {
            $query->set_404(); // غیرفعال کردن جستجو
        }
    }
    add_action('pre_get_posts', 'disable_search_queries');

    function optimize_wp_queries($query)
    {
        if (! is_admin() && $query->is_main_query()) {
            $query->set('no_found_rows', true);           // غیرفعال کردن pagination count
            $query->set('update_post_meta_cache', false); // غیرفعال کردن کش متا
            $query->set('update_post_term_cache', false); // غیرفعال کردن کش ترم‌ها
        }
    }
    add_action('pre_get_posts', 'optimize_wp_queries');

}

function remove_wp_version()
{
    return ''; // حذف نسخه وردپرس
}
add_filter('the_generator', 'remove_wp_version');

function remove_wp_version_from_scripts($src)
{
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src); // حذف نسخه از اسکریپت‌ها و استایل‌ها
    }
    return $src;
}
add_filter('style_loader_src', 'remove_wp_version_from_scripts', 9999);
add_filter('script_loader_src', 'remove_wp_version_from_scripts', 9999);

function hide_theme_name()
{
    wp_dequeue_style('parent-style'); // غیرفعال کردن استایل‌های قالب والد
    wp_dequeue_style('child-style');  // غیرفعال کردن استایل‌های قالب فرزند
    wp_deregister_style('parent-style');
    wp_deregister_style('child-style');
}
add_action('wp_enqueue_scripts', 'hide_theme_name', 9999);

function disable_rest_api()
{
    if (! is_user_logged_in()) {
        wp_die(__('REST API is disabled.', 'textdomain'));
    }
}
add_action('rest_api_init', 'disable_rest_api', 1);

function remove_wp_headers()
{
    remove_action('wp_head', 'wp_generator');                      // حذف نسخه وردپرس از هدر
    remove_action('wp_head', 'rsd_link');                          // حذف RSD link
    remove_action('wp_head', 'wlwmanifest_link');                  // حذف Windows Live Writer link
    remove_action('wp_head', 'rest_output_link_wp_head', 10);      // حذف لینک REST API
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10); // حذف لینک oEmbed
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);          // حذف لینک کوتاه
}
add_action('init', 'remove_wp_headers');

// function schedule_my_custom_cron()
// {
//     if (! wp_next_scheduled('avin_it_cron_job')) {
//         wp_schedule_event(time(), 'every_second', 'avin_it_cron_job');
//     }
// }
// add_action('wp', 'schedule_my_custom_cron');
