<?php
(defined('ABSPATH')) || exit;

add_action('admin_menu', 'mph_admin_menu');

/**
 * Fires before the administration menu loads in the admin.
 *
 * @param string $context Empty context.
 */
function mph_admin_menu(string $context): void
{

    $list_suffix = add_menu_page(
        'مسابقه آنی',
        'مسابفه آنی',
        'manage_options',
        'oni',
        'list_ayeh',
        'dashicons-hammer',
        2
    );

    add_submenu_page(
        'oni',
        'لیست آیه ها',
        'لیست آیه ها',
        'manage_options',
        'oni',
        'list_ayeh',
    );

    function list_ayeh()
    {
        $oni_option = oni_start_working();

        require_once ONI_VIEWS . 'menu/list.php';

    }

    $add_ayeh_suffix = add_submenu_page(
        'oni',
        'افزودن آیه ',
        'افزودن آیه ',
        'manage_options',
        'add_ayeh',
        'add_ayeh',
    );

    function add_ayeh()
    {
        $oni_option = oni_start_working();

        require_once ONI_VIEWS . 'menu/add.php';

    }

    $setting_suffix = add_submenu_page(
        'oni',
        'تنظیمات',
        'تنظیمات',
        'manage_options',
        'oni_setting',
        'setting_panels',
    );

    function setting_panels()
    {
        $oni_option = oni_start_working();

        require_once ONI_VIEWS . 'menu/setting.php';

    }

    $sms_panels_suffix = add_submenu_page(
        'oni',
        'تنظیمات پنل پیامک',
        'تنظیمات پنل پیامک',
        'manage_options',
        'sms_panels',
        'oni_sms_panels',
    );

    function oni_sms_panels()
    {
        $oni_option = oni_start_working();

        require_once ONI_VIEWS . 'menu/setting_sms_panels.php';

    }

    add_action('load-' . $list_suffix, 'oni__submit');
    add_action('load-' . $add_ayeh_suffix, 'oni__submit_add');
    add_action('load-' . $setting_suffix, 'oni__submit');
    add_action('load-' . $sms_panels_suffix, 'oni__submit');

    function oni__submit()
    {

        if (isset($_POST[ 'oni_act' ]) && $_POST[ 'oni_act' ] == 'oni__submit') {

            if (wp_verify_nonce($_POST[ '_wpnonce' ], 'oni_nonce' . get_current_user_id())) {
                if (isset($_POST[ 'tsms' ])) {
                    $_POST[ 'tsms' ] = array_map('sanitize_text_field', $_POST[ 'tsms' ]);
                }
                if (isset($_POST[ 'ghasedaksms' ])) {
                    $_POST[ 'ghasedaksms' ] = array_map('sanitize_text_field', $_POST[ 'ghasedaksms' ]);
                }

                oni_update_option($_POST);

                wp_admin_notice(
                    'تغییر شما با موفقیت ثبت شد',
                    [
                        'id'                 => 'message',
                        'type'               => 'success',
                        'additional_classes' => [ 'updated' ],
                        'dismissible'        => true,
                     ]
                );

            } else {
                wp_admin_notice(
                    'ذخیره سازی به مشکل خورده دوباره تلاش کنید',
                    [
                        'id'                 => 'oni_message',
                        'type'               => 'error',
                        'additional_classes' => [ 'updated' ],
                        'dismissible'        => true,
                     ]
                );

            }

        }

    }

    function oni__submit_add()
    {

        if (isset($_POST[ 'oni_act' ]) && $_POST[ 'oni_act' ] == 'oni__submit_question') {

            if (wp_verify_nonce($_POST[ '_wpnonce' ], 'oni_nonce' . get_current_user_id())) {

                $onidb = new ONIDB('question');

                $data = [
                    'title'   => wp_kses_post(wp_unslash(nl2br($_POST[ 'question' ]))),
                    'option1' => sanitize_text_field($_POST[ 'option1' ]),
                    'option2' => sanitize_text_field($_POST[ 'option2' ]),
                    'option3' => sanitize_text_field($_POST[ 'option3' ]),
                    'option4' => sanitize_text_field($_POST[ 'option4' ]),
                    'answer'  => absint($_POST[ 'answer' ]),
                 ];

                $format = [
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                 ];

                $unsert_num = $onidb->insert($data, $format);

                if ($unsert_num) {

                    wp_admin_notice(
                        'سوال شما با موفقیت ثبت شده است.  <a href="">ویرایش  سوال' . $unsert_num . '</a>',
                        [
                            'id'                 => 'oni_message',
                            'type'               => 'success',
                            'additional_classes' => [ 'updated' ],
                            'dismissible'        => true,
                         ]
                    );

                } else {
                    wp_admin_notice(
                        'ثبت سوال به مشکل خورده دوباره تلاش کنید',
                        [
                            'id'                 => 'oni_message',
                            'type'               => 'error',
                            'additional_classes' => [ 'updated' ],
                            'dismissible'        => true,
                         ]
                    );

                }

            } else {
                wp_admin_notice(
                    'ذخیره سازی به مشکل خورده دوباره تلاش کنید',
                    [
                        'id'                 => 'oni_message',
                        'type'               => 'error',
                        'additional_classes' => [ 'updated' ],
                        'dismissible'        => true,
                     ]
                );

            }

        }

    }

}
