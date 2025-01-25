<?php

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

add_action('init', 'action_init');

function action_init(): void
{
    if (isset($_POST[ 'oni_activation' ]) && $_POST[ 'oni_activation' ] == 'question') {

        $matchdl = new ONIDB('match');
        $onidb   = new ONIDB('question');

        $count_true = 0;
        $uuid       = '';

        while (true) {
            $uuid = generate_uuid();
            if (! $matchdl->num([ 'eid' => $uuid ])) {break;}
        }
        $question_list = sanitize_text_no_item(explode(',', $_POST[ 'question_list' ]));
        foreach ($question_list as $question) {

            $this_question = $onidb->get([ 'id' => $question ]);

            if ($this_question->answer == absint($_POST[ 'Q' . $question ])) {$count_true++;}

        }

        $insert_match = $matchdl->insert([
            'eid'             => $uuid,
            'iduser'          => get_current_user_id(),
            'count_questions' => count($question_list),
            'count_true'      => $count_true,

         ]);
        if ($insert_match) {
            $all_user_questions   = absint(get_user_meta(get_current_user_id(), 'questions', true));
            $all_user_count_true  = absint(get_user_meta(get_current_user_id(), 'count_true', true));
            $all_user_count_match = absint(get_user_meta(get_current_user_id(), 'count_match', true));

            update_user_meta(get_current_user_id(), 'questions', ($all_user_questions + count($question_list)));
            update_user_meta(get_current_user_id(), 'count_true', ($all_user_count_true + $count_true));
            update_user_meta(get_current_user_id(), 'count_match', ($all_user_count_match + 1));

        }
        ob_start();

        ob_end_flush();
        wp_redirect('/?eid=' . $uuid );
        exit;
    }

}
