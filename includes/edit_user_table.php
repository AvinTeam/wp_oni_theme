<?php

use oniclass\oni_export;

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
    $columns[ 'count_score' ] = 'امتیاز';
    return $columns;
}

add_action('manage_users_custom_column', 'show_institute_posts_count', 10, 3);
function show_institute_posts_count($output, $column_name, $user_id)
{
    $oni_export     = new oni_export('match');
    $all_info_match = $oni_export->get_all_info_match($user_id);

    if ($column_name === 'count_match') {return $all_info_match->total_match;}
    if ($column_name === 'questions') {return $all_info_match->total_count_questions;}
    if ($column_name === 'count_true') {return $all_info_match->total_count_true;}
    if ($column_name === 'count_score') {return $all_info_match->total_score;}

}

function add_csv_export_button()
{
    if (is_admin() && 'users.php' === $GLOBALS[ 'pagenow' ]) {
        echo '<div class="alignleft actions"><a href="' . esc_url(add_query_arg('action', 'user_excel', get_current_relative_url())) . '" class="button button-primary">EXCEL شماره موبایل</a></div>';
    }
}
add_action('manage_users_extra_tablenav', 'add_csv_export_button');
