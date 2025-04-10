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

                    setcookie("setcookie_oni_nonce", wp_generate_password(20, true, true), time() + 1800, "/");

                    $massage = 'خوش آمدید، شما وارد شدید!';

                } else {

                    $user_id = wp_create_user($mobile, wp_generate_password(), $mobile . '@example.com');

                    if (! is_wp_error($user_id)) {

                        update_user_meta($user_id, 'mobile', $mobile);
                        wp_set_current_user($user_id);
                        wp_set_auth_cookie($user_id, true);

                        $massage = 'ثبت‌ نام با موفقیت انجام شد و شما وارد شدید!';

                        setcookie("setcookie_oni_nonce", wp_generate_password(20, true, true), time() + 1800, "/");

                    } else {
                        wp_send_json_error('لطفا دوباره تلاش کنید', 403);

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
    $this_user = wp_get_current_user();

    $user_next_match = get_user_meta(get_current_user_id(), 'user_next_match', true);

    if ($user_next_match > time() ) {
        setcookie("setcookie_oni_nonce", wp_generate_password(20, true, true), time() + 1800, "/");

        wp_send_json_error('صفحه را یکیار به روزرسانی کنید');
    }


    if (! isset($_POST[ 'start_match' ]) &&
        ! isset($_POST[ 'send_match_user' ])) {
        error_log(print_r([
            'isuser' => get_current_user_id(),
            'mobile' => $this_user->mobile,

         ]
            , true));
    }

    if (
        isset($_POST[ '_wpnonce' ]) &&
        isset($_POST[ 'start_match' ]) &&
        isset($_POST[ 'send_match_user' ]) &&
        wp_verify_nonce($_POST[ '_wpnonce' ], 'oni_send_question_list' . oni_cookie()) &&
        (time() - intval($_POST[ 'start_match' ])) > 5 &&
        intval($_POST[ 'send_match_user' ]) == 1
    ) {

        usleep(rand(0, 50000));

        setcookie("setcookie_oni_nonce", wp_generate_password(20, true, true), time() + 1800, "/");

        $oni_option = oni_start_working();

        $matchdl    = new ONIDB('match');
        $onidb      = new ONIDB('question');
        $crondb     = new ONIDB('cron');
        $oni_export = new oni_export('match');

        $count_true = 0;

        $all_today = $oni_export->get_today();

        $eid = absint($all_today->total_rows);

        if ($eid >= ONI_END_MATCH) {
            setcookie("setcookie_oni_nonce", wp_generate_password(20, true, true), time() + 1800, "/");

            wp_send_json_error('صفحه را یکیار به روزرسانی کنید');
        }

        $question_list = sanitize_text_no_item(explode(',', $_POST[ 'question_list' ]));

        $array_game = $true_questions = [  ];

        $date = [
            'data' => [
                'id' => $question_list,

             ],
         ];

        $my_this_question = $onidb->select($date);

        foreach ($my_this_question as $question) {

            if ($question->answer == absint($_POST[ 'Q' . $question->id ])) {

                $true_questions[  ] = $question->id;

                $array_game[  ] = [
                    'score'          => ONI_QUESTION_SCORE,
                    'chapter'        => $question->chapter,
                    'chapter_number' => $question->chapter_number,
                    'verse'          => $question->verse,
                    'type'           => $question->q_type,

                 ];
                $count_true++;
            }

        }

        $insert_match = $matchdl->insert([
            'eid'             => absint($eid) + 1,
            'iduser'          => get_current_user_id(),
            'count_questions' => count($question_list),
            'true_questions'  => serialize($true_questions),
            'count_true'      => $count_true,
            'score'           => $count_true * ONI_QUESTION_SCORE,
            'created_at'      => date('Y-m-d H:i:s'),
         ]);

        $array_send = [
            'mobile'      => $this_user->mobile,
            'description' => "id game " . $insert_match,
            'game_type'   => 'online',
            'game'        => $array_game,
         ];

        if ($insert_match) {

            if ($oni_option[ 'send_cron' ] == 'yes' && $count_true) {

                $crondb->insert([
                    'cron_type'  => 'game',
                    'send_array' => serialize($array_send),
                 ]);

            }

            $score = $count_true * ONI_QUESTION_SCORE;

            if ((absint($eid) + 1) < 60 ) {
                update_user_meta(get_current_user_id(), 'user_next_match', (time() - 2 + (60 * ONI_NEXT_MATCH)));

            }

            wp_send_json_success([
                'score'      => $score,
                'count_true' => $count_true,
             ]);

        }
        wp_send_json_error('مشکلی پیش آمده اطفا دوباره تلاش کنید');
    }

    setcookie("setcookie_oni_nonce", wp_generate_password(20, true, true), time() + 1800, "/");

    wp_send_json_error('صفحه را یکیار به روزرسانی کنید');
}

add_action('wp_ajax_oniAjaxAllMatch', 'oniAjaxAllMatch');
function oniAjaxAllMatch()
{
    // wp_send_json_success($_POST);

    $_POST[ 'paged' ] = absint($_POST[ 'paged' ]) - 1;
    $offset           = (isset($_POST[ 'paged' ]) && absint($_POST[ 'paged' ])) ? (ONI_PER_PAGE * absint($_POST[ 'paged' ])) : 0;

    $next_offset = $offset + ONI_PER_PAGE;

    if ($_POST[ 'type' ] == 'all-match') {
        $matchdl = new oni_export('match');

        $string_all_match = '';
        $array            = $matchdl->get_by_user(
            $_POST[ 'date' ] ? sanitize_text_field($_POST[ 'date' ]) : '',
            $_POST[ 'sort' ] ? "total_count_true {$_POST[ 'sort' ]}" : '',
            $offset
        );

        if (sizeof($array) == 0) {
            if (! empty($_POST[ 'date' ])) {
                wp_send_json_error('<div class="alert alert-secondary" role="alert">شما در تاریخ ' . $_POST[ 'date' ] . ' در مسابقه ای شرکت نکردید.</div>');
            }
            wp_send_json_error('<div class="alert alert-secondary" role="alert">شما در مسابقه ای شرکت نکردید</div>');
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

        $arraynext = $matchdl->get_by_user(
            $_POST[ 'date' ] ? sanitize_text_field($_POST[ 'date' ]) : '',
            '',
            $next_offset
        );

        $response = [
            'massage' => $string_all_match,
            'prev'    => ($offset) ? 1 : 0,
            'next'    => (sizeof($arraynext)) ? 1 : 0,
         ];

        wp_send_json_success($response);

    } elseif ($_POST[ 'type' ] == 'today-match') {

        $this_date = date('Y-m-d');

        $onidb = new ONIDB('match');

        $string_all_match = '';

        $args = [
            'data'     => [ 'iduser' => get_current_user_id() ],
            'where'    => "DATE(`created_at`) = '$this_date'",
            'per_page' => ONI_PER_PAGE,
            'offset'   => $offset,

         ];

        if (absint($_POST[ 'sort' ])) {
            $args[ 'order_by' ] = [ "count_true", $_POST[ 'sort' ] ];
        } else {
            $args[ 'order_by' ] = [ "created_at", 'DESC' ];
        }

        $array = $onidb->select($args);

        $m = sizeof($array);
        if (sizeof($array) == 0) {
            wp_send_json_error('<div class="alert alert-secondary" role="alert">شما امروز در مسابقه ای شرکت نکردید</div>');
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

        $args[ 'offset' ] = $next_offset;

        $arraynext = $onidb->select($args);

        $response = [
            'massage' => $string_all_match,
            'prev'    => ($offset) ? 1 : 0,
            'next'    => (sizeof($arraynext)) ? 1 : 0,
         ];

        wp_send_json_success($response);

        wp_send_json_success($string_all_match);

    }
    wp_send_json_error('<div class="alert alert-danger" role="alert">خطایی پیش آمده دوباره تلاش کتید</div>');

}
