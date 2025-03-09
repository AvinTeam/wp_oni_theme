<?php

use oniclass\oni_export;

(defined('ABSPATH')) || exit;

function oni_template_path($oni_page = false)
{
    if (! $oni_page) {return;}

    if ($oni_page) {
        $oni_page = ($_SERVER[ 'REQUEST_METHOD' ] === 'POST' && isset($_POST[ 'act_user' ]) && $_POST[ 'act_user' ] == 'form_submit') ? 'panel' : $oni_page;
        $page     = explode('=', $oni_page);

        switch ($page[ 0 ]) {
            case 'city':
                $view = 'city';
                break;
            case 'province':
                $view = 'city';
            case 'all':
                $view = 'city';
            case 'search':
                $view = 'city';
                break;
            case 'logout':
                $view = 'logout';
                break;
            case 'panel':
                $view = (is_user_logged_in()) ? 'dashboard' : 'login';
                break;
            default:
                $view = '404';
                break;
        }

        return ONI_VIEWS . 'page/' . $view . '.php';

    }

    return false;

}

function oni_panel_js($path)
{
    return ONI_JS . $path . '?ver=' . ONI_VERSION;
}

function oni_panel_css($path)
{
    return ONI_CSS . $path . '?ver=' . ONI_VERSION;
}

function oni_panel_image($path)
{
    return ONI_IMAGE . $path . '?ver=' . ONI_VERSION;
}

function oni_remote(string $url)
{
    $res = wp_remote_get(
        $url,
        [
            'timeout' => 1000,
         ]);

    if (is_wp_error($res)) {
        $result = [
            'code'   => 1,
            'result' => $res->get_error_message(),
         ];
    } else {
        $result = [
            'code'   => 0,
            'result' => json_decode($res[ 'body' ]),
         ];
    }

    return $result;
}

function oni_start_working(): array
{
    $oni_option = get_option('oni_option');

    if (! isset($oni_option[ 'version' ]) || version_compare(ONI_VERSION, $oni_option[ 'version' ], '>')) {

        update_option(
            'oni_option',
            [
                'version'           => ONI_VERSION,
                'tsms'              => (isset($oni_option[ 'tsms' ])) ? $oni_option[ 'tsms' ] : [ 'username' => '', 'password' => '', 'number' => '' ],
                'ghasedaksms'       => (isset($oni_option[ 'ghasedaksms' ])) ? $oni_option[ 'ghasedaksms' ] : [ 'ApiKey' => '', 'number' => '' ],
                'sms_text_otp'      => (isset($oni_option[ 'sms_text_otp' ])) ? $oni_option[ 'sms_text_otp' ] : 'کد تأیید شما: %otp%',
                'set_timer'         => (isset($oni_option[ 'set_timer' ])) ? $oni_option[ 'set_timer' ] : 1,
                'set_code_count'    => (isset($oni_option[ 'set_code_count' ])) ? $oni_option[ 'set_code_count' ] : 4,
                'sms_type'          => (isset($oni_option[ 'sms_type' ])) ? $oni_option[ 'sms_type' ] : 'tsms',
                'notificator_token' => (isset($oni_option[ 'notificator_token' ])) ? $oni_option[ 'notificator_token' ] : '',

                'token_password'    => (isset($oni_option[ 'token_password' ])) ? $oni_option[ 'token_password' ] : 'super_secret-key',
                'send_cron'         => (isset($oni_option[ 'send_cron' ])) ? $oni_option[ 'send_cron' ] : 'no',

             ]

        );

        update_option('oni_crone_time', time());

    }

    return get_option('oni_option');

}

function oni_update_option($data)
{

    $oni_option = get_option('oni_option');

    $oni_option = [
        'version'           => ONI_VERSION,

        'tsms'              => (isset($data[ 'tsms' ])) ? $data[ 'tsms' ] : $oni_option[ 'tsms' ],
        'ghasedaksms'       => (isset($data[ 'ghasedaksms' ])) ? $data[ 'ghasedaksms' ] : $oni_option[ 'ghasedaksms' ],
        'set_timer'         => (isset($data[ 'set_timer' ])) ? absint($data[ 'set_timer' ]) : $oni_option[ 'set_timer' ],
        'set_code_count'    => (isset($data[ 'set_code_count' ])) ? absint($data[ 'set_code_count' ]) : $oni_option[ 'set_code_count' ],
        'sms_text_otp'      => (isset($data[ 'sms_text_otp' ])) ? sanitize_textarea_field($data[ 'sms_text_otp' ]) : $oni_option[ 'sms_text_otp' ],
        'sms_type'          => (isset($data[ 'sms_type' ])) ? sanitize_text_field($data[ 'sms_type' ]) : $oni_option[ 'sms_type' ],
        'notificator_token' => (isset($data[ 'notificator_token' ])) ? sanitize_text_field($data[ 'notificator_token' ]) : $oni_option[ 'notificator_token' ],

        'token_password'    => (isset($data[ 'token_password' ])) ? sanitize_text_field($data[ 'token_password' ]) : $oni_option[ 'token_password' ],
        'send_cron'         => (isset($data[ 'send_cron' ])) ? sanitize_text_field($data[ 'send_cron' ]) : $oni_option[ 'send_cron' ],

     ];

    update_option('oni_option', $oni_option);

}

function oni_massage_otp($otp)
{
    global $oni_option;

    // دریافت نام دامنه
    $server_name = isset($_SERVER[ 'HTTP_HOST' ]) ? $_SERVER[ 'HTTP_HOST' ] : $_SERVER[ 'SERVER_NAME' ];

    // جایگزینی کد OTP در متن پیام
    $finalMessage = str_replace('%otp%', $otp, $oni_option[ 'sms_text_otp' ]);

    // ساخت پیامک نهایی
    $message = "$finalMessage\n@$server_name #$otp";

    return $message;

    //$massage = $finalMessage . PHP_EOL . "@$server_name #$otp";
    //$massage = "$finalMessage\n@$server_name #$otp";
    // $massage = $finalMessage;

}

function oni_massage_format($data)
{
    global $oni_option;
    $server_name = $_SERVER[ 'SERVER_NAME' ];

    $finalMessage = str_replace([ '%username%', '%password%', '%url%' ], $data, $oni_option[ 'sms_text_format' ]);
    $massage      = $finalMessage . PHP_EOL . $server_name;

    return $massage;

}

function notificator($mobile, $massage)
{
    global $oni_option;

    $data = [
        'to'   => $oni_option[ 'notificator_token' ],
        'text' => $mobile . PHP_EOL . $massage,
     ];

    // درخواست POST با wp_remote_post
    $response = wp_remote_post('https://notificator.ir/api/v1/send', [
        'body' => $data,
     ]);

    $result = json_decode(wp_remote_retrieve_body($response));

    $result = [
        'code'    => $result->success,
        'massage' => ($result->success) ? 'پیام با موفقیت ارسال شد' : 'پیام به خطا خورده است ',
     ];

    return $result;

}

function tsms($mobile, $massage)
{

    global $oni_option;

    $msg_array = [ $massage ];

    $data = [
        'method'     => 'sendSms',
        'username'   => $oni_option[ 'tsms' ][ 'username' ],
        'password'   => $oni_option[ 'tsms' ][ 'password' ],
        'sms_number' => [ $oni_option[ 'tsms' ][ 'number' ] ],
        'mobile'     => [ $mobile ],
        'msg'        => $msg_array,
        'mclass'     => [ '' ],
        'messagid'   => rand(),
     ];

    $response = wp_remote_post('https://www.tsms.ir/json/json.php', [
        'body' => http_build_query($data),
     ]);

    $response = json_decode(wp_remote_retrieve_body($response));

    $result = [
        'code'    => (! is_null($response->code) && absint($response->code) == 200) ? 1 : intval($response->code),
        'massage' => (! is_null($response->code) && absint($response->code) == 200) ? 'پیام با موفقیت ارسال شد' : 'پیام به خطا خورده است',
     ];
    return $result;

}

function ghasedaksms($mobile, $massage)
{

    global $oni_option;
    $data = [
        'message'  => $massage,
        'sender'   => $oni_option[ 'ghasedaksms' ][ 'number' ],
        'receptor' => $mobile,
     ];
    $header = [
        'ApiKey' => $oni_option[ 'ghasedaksms' ][ 'ApiKey' ],
     ];

    $response = wp_remote_post('http://api.ghasedaksms.com/v2/sms/send/bulk2', [
        'headers' => $header,
        'body'    => http_build_query($data),
     ]);

    $response = json_decode(wp_remote_retrieve_body($response));

    if (isset($response->messageids) && $response->messageids == null) {
        error_log('mobile: ' . $mobile);
        error_log(print_r($response, true));
    }

    $result = [
        'code'    => (isset($response->result) && isset($response->messageids) && $response->result == 'success' && strlen($response->messageids) > 5) ? 1 : $response->messageids,
        'massage' => (isset($response->result) && isset($response->messageids) && $response->result == 'success' && strlen($response->messageids) > 5) ? 'پیام با موفقیت ارسال شد' : 'پیام به خطا خورده است',
     ];
    return $result;

}

function oni_send_sms($mobile, $type, $data = [  ])
{

    global $oni_option;

    // بررسی فرمت شماره موبایل
    if (! preg_match('/^09[0-9]{9}$/', $mobile)) {
        return [
            'code'    => -1,
            'massage' => 'شماره موبایل معتبر نیست.',
         ];
    }

    if ($type == 'otp') {

        if (get_transient('otp_' . $mobile)) {
            return [
                'code'    => -2,
                'massage' => 'لطفا چند دقیقه دیگر تلاش کنید.',
             ];
        }

        $otp = '';

        for ($i = 0; $i < $oni_option[ 'set_code_count' ]; $i++) {
            $otp .= rand(0, 9);
        }

        $result = $oni_option[ 'sms_type' ]($mobile, oni_massage_otp($otp));

        if ($result[ 'code' ] == 1) {
            set_transient('otp_' . $mobile, $otp, $oni_option[ 'set_timer' ] * MINUTE_IN_SECONDS);

        } else {
            delete_transient('otp_' . $mobile);

        }
    }

    return $result;
}

function oni_cookie(): string
{

    if (! is_user_logged_in()) {

        if (! isset($_COOKIE[ "setcookie_oni_nonce" ])) {

            $is_key_cookie = oni_rand_string(15);
            ob_start();

            setcookie("setcookie_oni_nonce", $is_key_cookie, time() + 1800, "/");

            ob_end_flush();

            header("Refresh:0");
            exit;

        } else {
            $is_key_cookie = $_COOKIE[ "setcookie_oni_nonce" ];
        }
    } else {

        $is_key_cookie = get_current_user_id();

    }
    return $is_key_cookie;
}

function oni_rand_string($length = 20)
{
    $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // اعداد و حروف
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[ rand(0, $charactersLength - 1) ];
    }
    return $randomString;
}

function oni_mask_mobile($mobile)
{
    // بررسی طول شماره موبایل
    if (strlen($mobile) === 11) {
        $lastFour = substr($mobile, -4); // گرفتن 4 رقم آخر

        $masked = $lastFour . "*****" . substr($mobile, 0, 4);

        return $masked;
    }
    return "شماره موبایل نامعتبر است.";
}

function tarikh($data, $type = '')
{

    $data_array = explode(" ", $data);

    $data = $data_array[ 0 ];
    $time = (sizeof($data_array) >= 2) ? $data_array[ 1 ] : 0;

    $has_mode = (strpos($data, '-')) ? '-' : '/';

    list($y, $m, $d) = explode($has_mode, $data);

    $ch_date = (strpos($data, '-')) ? gregorian_to_jalali($y, $m, $d, '/') : jalali_to_gregorian($y, $m, $d, '-');

    if ($type == 'time') {
        $new_date = $time;
    } elseif ($type == 'date') {
        $new_date = $ch_date;
    } else {
        $new_date = ($time === 0) ? $ch_date : $ch_date . ' ' . $time;
    }

    return $new_date;

}

function get_name_by_id($data, $id)
{
    $filtered = array_filter($data, function ($item) use ($id) {
        return $item->id == $id;
    });

    // برگرداندن اولین مقدار پیدا شده
    if (! empty($filtered)) {
        return array_values($filtered)[ 0 ]->name;
    }
    return null;
}

function oni_page_item($item)
{
    return '<div class="px-3 py-1 rounded bg-white oni_page_item fw-bold">' . $item . '</div>';
}

function get_current_relative_url()
{
    // گرفتن مسیر فعلی بدون دامنه
    $path = esc_url_raw(wp_unslash($_SERVER[ 'REQUEST_URI' ]));

                                                // حذف دامنه و فقط نگه داشتن مسیر نسبی + پارامترها
    $relative_url = strtok($path, '?');         // مسیر قبل از پارامترها
    $query_string = $_SERVER[ 'QUERY_STRING' ]; // پارامترهای GET

    // اگر پارامتر وجود داره، به مسیر اضافه کن
    if ($query_string) {
        $relative_url .= '?' . $query_string;
    }

    return $relative_url;
}

function oni_to_enghlish($text)
{

    $western = [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ];
    $persian = [ '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ];
    $arabic  = [ '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' ];
    $text    = str_replace($persian, $western, $text);
    $text    = str_replace($arabic, $western, $text);
    return $text;

}

function sanitize_phone($phone)
{

    /**
     * Convert all chars to en digits
     */

    $phone = oni_to_enghlish($phone);

    //.9158636712   => 09158636712
    if (strpos($phone, '.') === 0) {
        $phone = '0' . substr($phone, 1);
    }

    //00989185223232 => 9185223232
    if (strpos($phone, '0098') === 0) {
        $phone = substr($phone, 4);
    }
    //0989108210911 => 9108210911
    if (strlen($phone) == 13 && strpos($phone, '098') === 0) {
        $phone = substr($phone, 3);
    }
    //+989156040160 => 9156040160
    if (strlen($phone) == 13 && strpos($phone, '+98') === 0) {
        $phone = substr($phone, 3);
    }
    //+98 9156040160 => 9156040160
    if (strlen($phone) == 14 && strpos($phone, '+98 ') === 0) {
        $phone = substr($phone, 4);
    }
    //989152532120 => 9152532120
    if (strlen($phone) == 12 && strpos($phone, '98') === 0) {
        $phone = substr($phone, 2);
    }
    //Prepend 0
    if (strpos($phone, '0') !== 0) {
        $phone = '0' . $phone;
    }
    /**
     * check for all character was digit
     */
    if (! ctype_digit($phone)) {
        return '';
    }

    if (strlen($phone) != 11) {
        return '';
    }

    return $phone;

}

function oni_transient()
{
    $oni_transient = get_transient('oni_transient');

    if ($oni_transient) {
        delete_transient('oni_transient');
        return $oni_transient;
    }

}

function is_mobile($mobile)
{
    $pattern = '/^(\+98|0)?9\d{9}$/';
    return preg_match($pattern, $mobile);
}

function sanitize_text_no_item($item)
{
    $new_item = [  ];

    foreach ($item as $value) {
        if (empty($value)) {continue;}
        $new_item[  ] = sanitize_text_field($value);
    }

    return $new_item;

}

function oni_exam()
{

    // $onidb    = new ONIDB('question');
    // $all_ayeh = $onidb->select();

    // $random_numbers = [  ];
    // $exam           = [  ];
    // $answers        = [  ];
    // $stop_condition = false;

    // while (! $stop_condition) {
    //     $random_number = rand(1, $onidb->num() - 1);

    //     if (! in_array($random_number, $random_numbers)) {
    //         $random_numbers[  ] = $random_number;

    //         $ayeh                       = $all_ayeh[ $random_number ];
    //         $exam[  ]                   = $ayeh;
    //         $answers[ 'Q' . $ayeh->id ] = $ayeh->answer;

    //         if (count($random_numbers) == 5) {
    //             $stop_condition = true;
    //         }
    //     }
    // }

    $oni_export = new oni_export('question');
    $my_exam    = $oni_export->get_exam();

    $GLOBALS[ 'exam' ] = $my_exam->exam;

    return $my_exam->answers;
}

function generate_uuid()
{
    // تولید UUID به فرمت استاندارد
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

function q_name_row(int $index, int $type = 0): string
{

    switch ($index) {
        case 1:
            $name_row = $type ? 'اول' : 'یک';
            break;

        case 2:
            $name_row = $type ? 'دوم' : 'دو';
            break;

        case 3:
            $name_row = $type ? 'سوم' : 'سه';
            break;

        case 4:
            $name_row = $type ? 'چهارم' : 'چهار';
            break;

        case 5:
            $name_row = $type ? 'پنجم' : 'پنج';
            break;

        default:
            $name_row = '';
            break;
    }

    return $name_row;

}
