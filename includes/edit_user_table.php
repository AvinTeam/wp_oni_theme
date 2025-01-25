<?php
(defined('ABSPATH')) || exit;

add_filter('manage_users_columns', 'add_institute_posts_column');
function add_institute_posts_column($columns)
{
    if (isset($columns[ 'email' ])) {
        unset($columns[ 'email' ]);
    }
    if (isset($columns[ 'posts' ])) {
        unset($columns[ 'posts' ]);
    }
    $columns[ 'count_match' ] = 'شرکت در مسابقه';
    $columns[ 'questions' ]   = 'تعداد سوال';
    $columns[ 'count_true' ]  = 'پاسخ درست';
    return $columns;
}

add_action('manage_users_custom_column', 'show_institute_posts_count', 10, 3);
function show_institute_posts_count($output, $column_name, $user_id)
{
    $all_user_count_match = absint(get_user_meta($user_id, 'count_match', true));
    $all_user_questions   = absint(get_user_meta($user_id, 'questions', true));
    $all_user_count_true  = absint(get_user_meta($user_id, 'count_true', true));

    if ($column_name === 'count_match') {return $all_user_count_match;}
    if ($column_name === 'questions') {return $all_user_questions;}
    if ($column_name === 'count_true') {return $all_user_count_true;}

}


function add_csv_export_button()
{
    if (is_admin() && 'users.php' === $GLOBALS[ 'pagenow' ]) {
        echo '<div class="alignleft actions"><a href="' . esc_url(add_query_arg('action', 'user_csv', get_current_relative_url())) . '" class="button button-primary">خروجی CSV</a></div>';
        echo '<div class="alignleft actions"><a href="' . esc_url(add_query_arg('action', 'user_exel', get_current_relative_url())) . '" class="button button-primary">خروجی EXEL</a></div>';
    }
}
add_action('manage_users_extra_tablenav', 'add_csv_export_button');
