<?php

use oniclass\ONIDB;

add_action('wp_ajax_nopriv_oni_sent_sms', 'oni_sent_sms');

function oni_sent_sms()
{
    if (wp_verify_nonce($_POST[ 'nonce' ], 'ajax-nonce' . oni_cookie())) {
        if ($_POST[ 'mobileNumber' ] !== "") {
            $mobile = sanitize_phone($_POST[ 'mobileNumber' ]);

            $oni_send_sms = oni_send_sms($mobile, 'otp');

            if ($oni_send_sms[ 'code' ] == 1) {
                wp_send_json_success($oni_send_sms[ 'massage' ]);
            }
            wp_send_json_error($oni_send_sms[ 'massage' ], 403);

        }
        wp_send_json_error('شماره شما به درستی وارد نشده است', 403);

    } else {
        wp_send_json_error('لطفا یکبار صفحه را بروزرسانی کنید', 403);
    }

}

add_action('wp_ajax_nopriv_oni_sent_verify', 'oni_sent_verify');

function oni_sent_verify()
{
    if (wp_verify_nonce($_POST[ 'nonce' ], 'ajax-nonce' . oni_cookie())) {

        if ($_POST[ 'mobileNumber' ] !== "" && $_POST[ 'otpNumber' ] !== "") {

            $mobile = sanitize_text_field($_POST[ 'mobileNumber' ]);
            $otp    = sanitize_text_field($_POST[ 'otpNumber' ]);

            // دریافت کد ذخیره‌شده
            $saved_otp = get_transient('otp_' . $mobile);

            if (! $saved_otp || $saved_otp !== $otp) {
                wp_send_json_error('کد تأیید اشتباه یا منقضی شده است. ', 403);
            } else {

                $user_query = new WP_User_Query([
                    'meta_key'   => 'mobile',
                    'meta_value' => $mobile,
                    'number'     => 1,
                 ]);

                if (! empty($user_query->get_results())) {
                    $user = $user_query->get_results()[ 0 ];
                    wp_set_current_user($user->ID);
                    wp_set_auth_cookie($user->ID);

                    $massage = 'خوش آمدید، شما وارد شدید!';

                } else {

                    $user_id = wp_create_user($mobile, wp_generate_password(), $mobile . '@example.com');

                    if (! is_wp_error($user_id)) {
                        update_user_meta($user_id, 'mobile', $mobile);
                        update_user_meta($user_id, 'questions', 0);
                        update_user_meta($user_id, 'count_true', 0);
                        update_user_meta($user_id, 'count_match', 0);
                        wp_set_current_user($user_id);
                        wp_set_auth_cookie($user_id);

                        $massage = 'ثبت‌ نام با موفقیت انجام شد و شما وارد شدید!';
                    }

                }

                delete_transient('otp_' . $mobile);

                wp_send_json_success($massage);

            }
        }
    } else {
        wp_send_json_error('لطفا یکبار صفحه را بروزرسانی کنید', 403);

    }
    wp_send_json_error('لطفا دوباره تلاش کنید', 403);

}

add_action('wp_ajax_oni_update_row', 'oni_update_row');

function oni_update_row()
{
    $onidb = new ONIDB('question');

    if (intval($_POST[ 'dataId' ])) {

        $delete_row = $onidb->delete(
            [
                'id' => intval($_POST[ 'dataId' ]),
             ]
        );
        if ($delete_row) {

            wp_send_json_success($delete_row);

        }
        wp_send_json_error('حذف انجام نشد', 403);

    } else {
        wp_send_json_error('خطا در ارسال اطلاعات', 403);
    }

}

add_action('wp_ajax_oni_logout', 'oni_logout');

function oni_logout()
{

    wp_logout();

    wp_send_json_success('https://zendegibaayeha.ir');

}

add_action('wp_ajax_oni_del_all_question', 'oni_del_all_question');

function oni_del_all_question()
{
    $onidb = new ONIDB('question');

    $onidb->empty();

    wp_send_json_success('ok');

}

add_action('wp_ajax_oni_sent_question', 'oni_sent_question');

function oni_sent_question()
{

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

        $count_all = ($all_user_count_true + $count_true);

        update_user_meta(get_current_user_id(), 'questions', ($all_user_questions + count($question_list)));
        update_user_meta(get_current_user_id(), 'count_true', $count_all);
        update_user_meta(get_current_user_id(), 'count_match', ($all_user_count_match + 1));

        wp_send_json_success([
            'count_all'  => $count_all,
            'count_true' => $count_true,
         ]);

    }
    wp_send_json_error('');

}