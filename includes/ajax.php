<?php

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
