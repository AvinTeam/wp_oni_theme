<?php

(defined('ABSPATH')) || exit;

add_action('admin_init', 'handle_download');

function handle_download()
{

    if (isset($_GET[ 'action' ])) {

        if ($_GET[ 'action' ] === 'user_excel') {
            $args = [
                'role' => 'subscriber',
             ];

            $user_query = new WP_User_Query($args);
            $users      = $user_query->get_results();

            $data = [  ];

            foreach ($users as $user) {
                $user_id = $user->ID;
                $mobile  = get_user_meta($user_id, 'mobile', true);
                if (is_mobile($mobile)) {continue;}
                $row[ 'شماره موبایل' ] = $mobile;

                $data[  ] = $row;
            }

            function filterData(&$str)
            {
                $str = preg_replace("/\t/", "\\t", $str);
                $str = preg_replace("/\r?\n/", "\\n", $str);
                if (strstr($str, '"')) {
                    $str = '"' . str_replace('"', '""', $str) . '"';
                }

            }

            $fileName = "users.xls";
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header("Content-type: application/octet-stream");
            header('Content-Transfer-Encoding: binary');
            header("Pragma: no-cache");
            header("Expires: 0");

            $flag = false;
            foreach ($data as $row) {
                if (! $flag) {
                    $key1 = implode("\t", array_keys($row)) . "\n";

                    echo chr(255) . chr(254) . iconv("UTF-8", "UTF-16LE//IGNORE", $key1);
                    $flag = true;
                }
                array_walk($row, 'filterData');
                $key2 = implode("\t", array_values($row)) . "\n";
                echo chr(255) . chr(254) . iconv("UTF-8", "UTF-16LE//IGNORE", $key2);
            }

            exit;

        }

    }

}
