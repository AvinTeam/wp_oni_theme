<?php

use oniclass\List_Table;
use oniclass\ONIDB;

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

        if (isset($_GET[ 'update_item' ]) && absint($_GET[ 'update_item' ])) {

            $GLOBALS[ 'title' ] = 'ویرایش';
            $onidb              = new ONIDB('question');

            $ayeh = $onidb->get([ 'id' => absint($_GET[ 'update_item' ]) ], ARRAY_A);

            require_once ONI_VIEWS . 'menu/add.php';

        } else {

            $oni_option   = oni_start_working();
            $oniListTable = new List_Table;

            require_once ONI_VIEWS . 'menu/list.php';
        }
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

        global $ayeh;

        if ($ayeh == null) {

            $ayeh = [
                'question' => '',
                'option1'  => '',
                'option2'  => '',
                'option3'  => '',
                'option4'  => '',
                'answer'   => 0,
             ];
        }
        require_once ONI_VIEWS . 'menu/add.php';

    }

    $add_file_ayeh_suffix = add_submenu_page(
        'oni',
        'افزودن با اکسل',
        'افزودن با اکسل',
        'manage_options',
        'add_file_ayeh',
        'add_file_ayeh',
    );

    function add_file_ayeh()
    {
        $oni_option = oni_start_working();

        require_once ONI_VIEWS . 'menu/add_file.php';

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

    $export_suffix = add_submenu_page(
        'oni',
        'خروجی اطلاعات',
        'خروجی اطلاعات',
        'manage_options',
        'oni_export',
        'oni_export',
    );

    function oni_export()
    {
        $oni_option = oni_start_working();

        require_once ONI_VIEWS . 'menu/export.php';

    }

    add_action('load-' . $list_suffix, 'oni__list');
    add_action('load-' . $add_ayeh_suffix, 'oni__submit_add');
    add_action('load-' . $setting_suffix, 'oni__submit');
    add_action('load-' . $sms_panels_suffix, 'oni__submit');
    add_action('load-' . $export_suffix, 'oni__export');
    add_action('load-' . $add_file_ayeh_suffix, 'oni__add_file');

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
                        'id'          => 'message',
                        'type'        => 'success',
                        'dismissible' => true,
                     ]
                );

            } else {
                wp_admin_notice(
                    'ذخیره سازی به مشکل خورده دوباره تلاش کنید',
                    [
                        'id'          => 'oni_message',
                        'type'        => 'error',
                        'dismissible' => true,
                     ]
                );

            }

        }

    }

    function oni__submit_add()
    {

        if (isset($_POST[ 'oni_act' ]) && $_POST[ 'oni_act' ] == 'oni__submit_question') {

            if (wp_verify_nonce($_POST[ '_wpnonce' ], 'oni_nonce' . get_current_user_id())) {

                $error = false;

                if (empty($_POST[ 'question' ])) {
                    $error = true;

                    wp_admin_notice(
                        'سوال مسابقه را وارد کنید',
                        [
                            'id'          => 'oni_message_question',
                            'type'        => 'error',
                            'dismissible' => true,
                         ]
                    );
                }

                if (empty($_POST[ 'option1' ])) {
                    $error = true;

                    wp_admin_notice(
                        'گزینه اول را وارد نکردید',
                        [
                            'id'          => 'oni_message_option1',
                            'type'        => 'error',
                            'dismissible' => true,
                         ]
                    );
                }

                if (empty($_POST[ 'option2' ])) {
                    $error = true;

                    wp_admin_notice(
                        'گزینه دوم را وارد نکردید',
                        [
                            'id'          => 'oni_message_option2',
                            'type'        => 'error',
                            'dismissible' => true,
                         ]
                    );
                }

                if (empty($_POST[ 'option3' ])) {
                    $error = true;

                    wp_admin_notice(
                        'گزینه سوم را وارد نکردید',
                        [
                            'id'          => 'oni_message_option3',
                            'type'        => 'error',
                            'dismissible' => true,
                         ]
                    );
                }

                if (empty($_POST[ 'option4' ])) {
                    $error = true;

                    wp_admin_notice(
                        'گزینه چهارم را وارد نکردید',
                        [
                            'id'          => 'oni_message_option4',
                            'type'        => 'error',
                            'dismissible' => true,
                         ]
                    );
                }

                if (! absint($_POST[ 'answer' ])) {

                    $error = true;

                    wp_admin_notice(
                        'پاسخ درست را وارد نکردید',
                        [
                            'id'          => 'oni_message_answer',
                            'type'        => 'error',
                            'dismissible' => true,
                         ]
                    );
                }

                if (! $error) {

                    $onidb = new ONIDB('question');

                    $unsert_num = $onidb->insert([
                        'question' => sanitize_textarea_field($_POST[ 'question' ]),
                        'option1'  => sanitize_text_field($_POST[ 'option1' ]),
                        'option2'  => sanitize_text_field($_POST[ 'option2' ]),
                        'option3'  => sanitize_text_field($_POST[ 'option3' ]),
                        'option4'  => sanitize_text_field($_POST[ 'option4' ]),
                        'answer'   => (isset($_POST[ 'answer' ])) ? absint($_POST[ 'answer' ]) : 0,
                     ]);

                    if ($unsert_num) {

                        wp_admin_notice(
                            'سوال شما با موفقیت ثبت شده است.  <a href="' . admin_url('admin.php?page=oni&update_item=' . $unsert_num) . '">ویرایش  سوال</a>',
                            [
                                'id'          => 'oni_message',
                                'type'        => 'success',
                                'dismissible' => true,
                             ]
                        );

                    } else {
                        wp_admin_notice(
                            'ثبت سوال به مشکل خورده دوباره تلاش کنید',
                            [
                                'id'          => 'oni_message',
                                'type'        => 'error',
                                'dismissible' => true,
                             ]
                        );

                    }

                } else {

                    $GLOBALS[ 'ayeh' ] = [
                        'question' => sanitize_textarea_field($_POST[ 'question' ]),
                        'option1'  => sanitize_text_field($_POST[ 'option1' ]),
                        'option2'  => sanitize_text_field($_POST[ 'option2' ]),
                        'option3'  => sanitize_text_field($_POST[ 'option3' ]),
                        'option4'  => sanitize_text_field($_POST[ 'option4' ]),
                        'answer'   => (isset($_POST[ 'answer' ])) ? absint($_POST[ 'answer' ]) : 0,
                     ];
                }

            } else {
                wp_admin_notice(
                    'ذخیره سازی به مشکل خورده دوباره تلاش کنید',
                    [
                        'id'                 => 'oni_message',
                        'type'               => 'error',
                        'additional_classes' => [ '' ],
                        'dismissible'        => true,
                     ]
                );

            }

        }

    }

    function oni__list()
    {

        if (isset($_POST[ 'action2' ])) {

            $onidb = new ONIDB('question');

            if (sanitize_text_field($_POST[ 'action2' ]) == 'delete') {

                foreach ($_POST[ 'oni_row' ] as $row) {

                    $onidb->delete(
                        [
                            'id' => intval($row),
                         ]
                    );

                }

                wp_admin_notice(
                    'سوال های انتخابی حدف شدند',
                    [
                        'id'                 => 'oni_message',
                        'type'               => 'success',
                        'additional_classes' => [ '' ],
                        'dismissible'        => true,
                     ]
                );

            }

        }

        if (isset($_POST[ 's' ])) {
            $redirect = '';
            if (! empty($_POST[ 's' ])) {
                $redirect = '&s=' . $_POST[ 's' ];
            }

            wp_redirect(admin_url('admin.php?page=oni' . $redirect));
        }

        if (isset($_POST[ 'oni_act' ]) && $_POST[ 'oni_act' ] == 'oni__submit_question' && isset($_GET[ 'update_item' ]) && absint($_GET[ 'update_item' ])) {

            if (wp_verify_nonce($_POST[ '_wpnonce' ], 'oni_nonce' . get_current_user_id())) {

                $error = false;

                if (empty($_POST[ 'question' ])) {
                    $error = true;

                    wp_admin_notice(
                        'سوال مسابقه را وارد کنید',
                        [
                            'id'          => 'oni_message_question',
                            'type'        => 'error',
                            'dismissible' => true,
                         ]
                    );
                }

                if (empty($_POST[ 'option1' ])) {
                    $error = true;

                    wp_admin_notice(
                        'گزینه اول را وارد نکردید',
                        [
                            'id'          => 'oni_message_option1',
                            'type'        => 'error',
                            'dismissible' => true,
                         ]
                    );
                }

                if (empty($_POST[ 'option2' ])) {
                    $error = true;

                    wp_admin_notice(
                        'گزینه دوم را وارد نکردید',
                        [
                            'id'          => 'oni_message_option2',
                            'type'        => 'error',
                            'dismissible' => true,
                         ]
                    );
                }

                if (empty($_POST[ 'option3' ])) {
                    $error = true;

                    wp_admin_notice(
                        'گزینه سوم را وارد نکردید',
                        [
                            'id'          => 'oni_message_option3',
                            'type'        => 'error',
                            'dismissible' => true,
                         ]
                    );
                }

                if (empty($_POST[ 'option4' ])) {
                    $error = true;

                    wp_admin_notice(
                        'گزینه چهارم را وارد نکردید',
                        [
                            'id'          => 'oni_message_option4',
                            'type'        => 'error',
                            'dismissible' => true,
                         ]
                    );
                }

                if (! absint($_POST[ 'answer' ])) {

                    $error = true;

                    wp_admin_notice(
                        'پاسخ درست را وارد نکردید',
                        [
                            'id'          => 'oni_message_answer',
                            'type'        => 'error',
                            'dismissible' => true,
                         ]
                    );
                }

                if (! $error) {

                    $onidb = new ONIDB('question');

                    $unsert_num = $onidb->update([
                        'question' => sanitize_textarea_field($_POST[ 'question' ]),
                        'option1'  => sanitize_text_field($_POST[ 'option1' ]),
                        'option2'  => sanitize_text_field($_POST[ 'option2' ]),
                        'option3'  => sanitize_text_field($_POST[ 'option3' ]),
                        'option4'  => sanitize_text_field($_POST[ 'option4' ]),
                        'answer'   => (isset($_POST[ 'answer' ])) ? absint($_POST[ 'answer' ]) : 0,
                     ],
                        [
                            'id' => absint($_GET[ 'update_item' ]),

                         ]
                    );

                    if ($unsert_num) {

                        wp_admin_notice(
                            'سوال با موفقیت ویرایش شد',
                            [
                                'id'                 => 'oni_message',
                                'type'               => 'success',
                                'additional_classes' => [ '' ],
                                'dismissible'        => true,
                             ]
                        );

                    } else {
                        wp_admin_notice(
                            'ویرایش سوال به مشکل خورده دوباره تلاش کنید',
                            [
                                'id'          => 'oni_message',
                                'type'        => 'error',
                                'dismissible' => true,
                             ]
                        );

                    }

                } else {

                    $GLOBALS[ 'ayeh' ] = [
                        'question' => sanitize_textarea_field($_POST[ 'question' ]),
                        'option1'  => sanitize_text_field($_POST[ 'option1' ]),
                        'option2'  => sanitize_text_field($_POST[ 'option2' ]),
                        'option3'  => sanitize_text_field($_POST[ 'option3' ]),
                        'option4'  => sanitize_text_field($_POST[ 'option4' ]),
                        'answer'   => (isset($_POST[ 'answer' ])) ? absint($_POST[ 'answer' ]) : 0,
                     ];
                }

            } else {
                wp_admin_notice(
                    'ذخیره سازی به مشکل خورده دوباره تلاش کنید',
                    [
                        'id'                 => 'oni_message',
                        'type'               => 'error',
                        'additional_classes' => [ '' ],
                        'dismissible'        => true,
                     ]
                );

            }

        }

    }

    function oni__export()
    {

        if (isset($_POST[ 'oni_act' ]) && $_POST[ 'oni_act' ] == "oni__export") {

            if (wp_verify_nonce($_POST[ '_wpnonce' ], 'oni_nonce' . get_current_user_id())) {

                require_once ONI_INCLUDES . 'export-file.php';

                exit;

            }
        }
    }

    function oni__add_file()
    {

        if (isset($_POST[ 'oni_act' ]) && $_POST[ 'oni_act' ] == "oni__import") {

            if (wp_verify_nonce($_POST[ '_wpnonce' ], 'oni_nonce' . get_current_user_id())) {

                require_once ONI_INCLUDES . 'import-file.php';

                if ($count_row) {
                    wp_admin_notice(
                        "تعداد $count_row سوال از اکسل فراخوانی شد.",
                        [
                            'id'          => 'oni_message',
                            'type'        => 'success',
                            'dismissible' => true,
                         ]
                    );
                } else {
                    wp_admin_notice(
                        'استخراج به مشکل خورده لطفا اکسل رو بررسی کنید و دوباره امتحان کنید',
                        [
                            'id'                 => 'oni_message',
                            'type'               => 'error',
                            'additional_classes' => [ '' ],
                            'dismissible'        => true,
                         ]
                    );
                }

            }
        }
    }

}
