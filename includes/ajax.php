<?php

use oniclass\ONIDB;
use oniclass\oni_export;

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
                    wp_set_auth_cookie($user->ID, true);

                    $massage = 'خوش آمدید، شما وارد شدید!';

                } else {

                    $user_id = wp_create_user($mobile, wp_generate_password(), $mobile . '@example.com');

                    if (! is_wp_error($user_id)) {
                        update_user_meta($user_id, 'mobile', $mobile);
                        update_user_meta($user_id, 'questions', 0);
                        update_user_meta($user_id, 'count_true', 0);
                        update_user_meta($user_id, 'count_match', 0);
                        wp_set_current_user($user_id);
                        wp_set_auth_cookie($user_id, true);

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
    wp_send_json_success(home_url());
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
    $oni_option = oni_start_working();

    $this_user = wp_get_current_user();

    $matchdl = new ONIDB('match');
    $onidb   = new ONIDB('question');
    $crondb  = new ONIDB('cron');

    $count_true = 0;

    $this_date = date('Y-m-d');

    $q = $matchdl->num([ 'iduser' => get_current_user_id() ], "DATE(`created_at`) = '$this_date'");

    $question_list = sanitize_text_no_item(explode(',', $_POST[ 'question_list' ]));

    $array_game = $true_questions = [  ];
    foreach ($question_list as $question) {

        $this_question = $onidb->get([ 'id' => $question ]);

        if ($this_question->answer == absint($_POST[ 'Q' . $question ])) {

            $true_questions[  ] = $question;
            $array_game[  ]     = [
                'score'          => ONI_QUESTION_SCORE,
                'chapter'        => $this_question->chapter,
                'chapter_number' => $this_question->chapter_number,
                'verse'          => $this_question->verse,
                'type'           => $this_question->q_type,

             ];

            $count_true++;

        }

    }

    $insert_match = $matchdl->insert([
        'eid'             => absint($q) + 1,
        'iduser'          => get_current_user_id(),
        'count_questions' => count($question_list),
        'true_questions'  => serialize($true_questions),
        'count_true'      => $count_true,
        'score'           => $count_true * ONI_QUESTION_SCORE,
     ]);

    $array_send = [
        'mobile'      => $this_user->mobile,
        'description' => "id game ".$insert_match,
        'game_type'   => 'online',
        'game'        => $array_game,
     ];

    if ($insert_match) {

        if ($oni_option[ 'send_cron' ] == 'yes' && $count_true) {

            $crondb->insert([
                'match_id'   => $insert_match,
                'send_array' => serialize($array_send),
             ]);

        }

        $all_user_questions   = absint(get_user_meta(get_current_user_id(), 'questions', true));
        $all_user_count_true  = absint(get_user_meta(get_current_user_id(), 'count_true', true));
        $all_user_count_match = absint(get_user_meta(get_current_user_id(), 'count_match', true));

        $count_all = ($all_user_count_true + $count_true);

        update_user_meta(get_current_user_id(), 'questions', ($all_user_questions + count($question_list)));
        update_user_meta(get_current_user_id(), 'count_true', $count_all);
        update_user_meta(get_current_user_id(), 'count_match', ($all_user_count_match + 1));

        wp_send_json_success([
            'score'      => $count_true * ONI_QUESTION_SCORE,
            'count_true' => $count_true,
         ]);

    }
    wp_send_json_error('');

}

add_action('wp_ajax_oniAjaxAllMatch', 'oniAjaxAllMatch');

function oniAjaxAllMatch()
{

    // wp_send_json_success($_POST);

    if ($_POST[ 'type' ] == 'all-match') {
        $matchdl = new oni_export('match');

        $string_all_match = '';
        $array            = $matchdl->get_by_user(
            get_current_user_id(),
            $_POST[ 'date' ] ? sanitize_text_field($_POST[ 'date' ]) : '',
            $_POST[ 'sort' ] ? "total_count_true {$_POST[ 'sort' ]}" : '',
        );

        if (sizeof($array) == 0) {
            if (! empty($_POST[ 'date' ])) {
                wp_send_json_success('<div class="alert alert-secondary" role="alert">شما در تاریخ ' . $_POST[ 'date' ] . ' در مسابقه ای شرکت نکردید.</div>');

            }
            wp_send_json_success('<div class="alert alert-secondary" role="alert">شما در مسابقه ای شرکت نکردید</div>');
        }

        foreach ($array as $row) {
            $string_all_match .= '
            <div class="w-100 bg-primary-100 d-flex flex-column rounded-8px ">
                <div class="d-flex flex-row justify-content-around align-items-center border-primary-200"
                    style="border-bottom: 1px solid">
                    <div class="p-12px w-100  border-primary-200 " style="border-left: 1px solid">
                        <span class="f-14px text-primary-600">روز مسابقه</span>
                        <div class="h-12px"></div>
                        <span class="fw-bold f-16px text-primary">' . tarikh($row->unique_date) . '</span>
                    </div>
                    <div class="p-12px w-100">
                        <span class="f-14px text-primary-600">تعداد دفعات شرکت</span>
                        <div class="h-12px"></div>
                        <span class="fw-bold f-16px text-primary">' . number_format($row->total_match) . '</span>
                    </div>
                </div>
                <div class="d-flex flex-row justify-content-around align-items-center">
                    <div class="p-12px w-100  border-primary-200 " style="border-left: 1px solid">
                        <span class="f-14px text-primary-600">نتیجه کل روز</span>
                        <div class="h-12px"></div>
                        <span class="fw-bold f-16px text-primary">نتیجه ' . number_format($row->total_count_true) . ' از ' . number_format($row->total_count_questions) . '</span>
                    </div>
                    <div class="p-12px w-100">
                        <span class="f-14px text-primary-600">امتیاز کسب شده در روز</span>
                        <div class="h-12px"></div>
                        <span class="fw-bold f-16px text-primary">' . number_format($row->total_score) . ' امتیاز</span>
                    </div>
                </div>
            </div>
            <div class="h-16px"></div>';
        }

        wp_send_json_success($string_all_match);

    } elseif ($_POST[ 'type' ] == 'today-match') {

        $this_date = date('Y-m-d');

        $onidb = new ONIDB('match');

        $string_all_match = '';

        $args = [
            'date'  => [ 'iduser' => get_current_user_id() ],
            'where' => "DATE(`created_at`) = '$this_date'",

         ];

        if (absint($_POST[ 'sort' ])) {
            $args[ 'order_by' ] = [ "count_true", $_POST[ 'sort' ] ];
        } else {
            $args[ 'order_by' ] = [ "created_at", 'DESC' ];

        }

        $array = $onidb->select($args);
        $m     = sizeof($array);
        if (sizeof($array) == 0) {
            wp_send_json_success('<div class="alert alert-secondary" role="alert">شما امروز در مسابقه ای شرکت نکردید</div>');
        }

        foreach ($array as $row) {

            $eid = $row->eid;
            if (! absint($row->eid)) {

                $data = [
                    'eid' => $m,
                 ];
                $where = [
                    'id' => $row->id,
                 ];

                $onidb->update($data, $where);

                $eid = $m;

            }
            $m--;

            $string_all_match .= '
            <div class="w-100 bg-primary-100 d-flex flex-column rounded-8px ">
                <div class="d-flex flex-row justify-content-around align-items-center border-primary-200"
                    style="border-bottom: 1px solid">
                    <div class="p-12px w-100  border-primary-200 " style="border-left: 1px solid">
                        <span class="f-14px text-primary-600">ساعت</span>
                        <div class="h-12px"></div>
                        <span class="fw-bold f-16px text-primary">' . tarikh($row->created_at, 'time') . '</span>
                    </div>
                    <div class="p-12px w-100">
                        <span class="f-14px text-primary-600">دفعه شرکت</span>
                        <div class="h-12px"></div>
                        <span class="fw-bold f-16px text-primary">' . ($eid) . '</span>
                    </div>
                </div>
                <div class="d-flex flex-row justify-content-around align-items-center">
                    <div class="p-12px w-100  border-primary-200 " style="border-left: 1px solid">
                        <span class="f-14px text-primary-600">نتیجه</span>
                        <div class="h-12px"></div>
                        <span class="fw-bold f-16px text-primary">نتیجه ' . number_format($row->count_true) . ' از ' . number_format($row->count_questions) . '</span>
                    </div>
                    <div class="p-12px w-100">
                        <span class="f-14px text-primary-600">امتیاز کسب شده</span>
                        <div class="h-12px"></div>
                        <span class="fw-bold f-16px text-primary">' . number_format($row->score) . ' امتیاز</span>
                    </div>
                </div>
            </div>
            <div class="h-16px"></div>';

        }

        wp_send_json_success($string_all_match);

    }
    wp_send_json_error('');

}
