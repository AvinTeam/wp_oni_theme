<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

(defined('ABSPATH')) || exit;

add_action('admin_init', 'handle_download');

function handle_download()
{

    if (isset($_GET[ 'action' ])) {

        if ($_GET[ 'action' ] === 'user_excel') {

            $args = [
                'meta_key'     => 'mobile',
                'meta_value'   => '09',
                'meta_compare' => 'LIKE',
            ];

            $user_query = new WP_User_Query($args);

            if (! empty($user_query->results)) {
                // ایجاد یک شیء Spreadsheet جدید
                $spreadsheet = new Spreadsheet();
                $sheet       = $spreadsheet->getActiveSheet();

                // تنظیم هدر ستون
                $sheet->setCellValue('A1', 'Mobile');

                // پر کردن داده‌ها
                $row = 2;
                foreach ($user_query->results as $user) {
                    $mobile = get_user_meta($user->ID, 'mobile', true);
                    $sheet->setCellValue('A' . $row, $mobile);
                    $row++;
                }

                // ارسال فایل اکسل به کاربر برای دانلود
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="users_ayeh_online.xlsx"');
                header('Cache-Control: max-age=0');

                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                exit;
            } else {
                echo 'هیچ کاربری یافت نشد.';
            }

        }

    }

}
