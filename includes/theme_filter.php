<?php
(defined('ABSPATH')) || exit;

function oni_title_filter($title)
{
    if (is_home() || is_front_page()) {
        $title = get_bloginfo('name');
    } elseif (is_single()) {
        $title = get_the_title() . " | " . get_bloginfo('name');
    } elseif (is_category()) {
        $title = single_cat_title('', false) . " | دسته‌بندی";
    } elseif (is_tag()) {
        $title = single_tag_title('', false) . " | برچسب";
    } elseif (is_search()) {
        $title = "نتایج جستجو برای " . get_search_query();
    } elseif (is_404()) {
        $title = get_bloginfo('name') . "صفحه پیدا نشد | ";
    } else {
        $title = get_bloginfo('name');
    }
    return $title;
}
add_filter('wp_title', 'oni_title_filter');

function custom_login_cookie_expiration($expiration)
{
    return 30 * DAY_IN_SECONDS;
}
add_filter('auth_cookie_expiration', 'custom_login_cookie_expiration');
