<?php

use oniclass\ONIDB;

(defined('ABSPATH')) || exit;

// افزودن اکشن کرون
//add_action('avin_it_cron_job', 'avin_it_cron_function');

$crondb        = new ONIDB('cron');
$cron_error_db = new ONIDB('cron_error');

// $crondb->insert([
//     'cron_type'  => 'ip',
//     'send_array' => [
//         'ip'            => $_SERVER[ 'REMOTE_ADDR' ],
//         'user-agent'    => $_SERVER[ 'HTTP_USER_AGENT' ],
//         'game_platform' => 'online',

//      ],
//  ]);

function avin_it_cron_function()
{
    $api_url = ' http://94.232.173.178:31963/api/';

    $crondb        = new ONIDB('cron');
    $cron_error_db = new ONIDB('cron_error');

    $my_res = $crondb->select([
        'per_page' => 50,
        'order_by' => [
            'id',
            'DESC',
         ],
        'data'     => [
            'cron_type' => 'game',
         ],
     ]);

    foreach ($my_res as $row) {

        $ap_u = ($row->cron_type == 'ip') ? 'ipLog' : 'rabbitMq';

        $error_message = '';

        $tracking = (absint($row->tracking) + 1);

        $data  = [ 'tracking' => $tracking ];
        $where = [ 'id' => $row->id ];
        $crondb->update($data, $where);

        $response = wp_remote_post(
            $api_url . $ap_u,
            [
                'timeout' => 1000,
                'headers' => [
                    'Authorization' => 'Bearer ' . ONI_TOKEN, // ارسال توکن در هدر
                    'Content-Type'  => 'application/json',    // نوع محتوای بدنه
                 ],
                'body'    => json_encode(unserialize($row->send_array)),
             ]);




             $response = wp_remote_post(
                $api_url . 'rabbitMq',
                [
                    'timeout' => 1000,
                    'headers' => [
                        'Authorization' => 'Bearer ' . ONI_TOKEN, // ارسال توکن در هدر
                        'Content-Type'  => 'application/json',    // نوع محتوای بدنه
                     ],
                    'body'    => json_encode(unserialize($row->send_array)),
                 ]);
    




        if (is_wp_error($response)) {

            $error_message = $response->get_error_message();

        } else {

            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body);

            if (isset($data->success) && $data->success) {

                $crondb->delete([ 'id' => $row->id ]);

            } else {

                $error_message = $data->message;

            }

        }

        if (! empty($error_message) && $tracking >= 5) {

            $crondb->delete([ 'id' => $row->id ]);

            $cron_error_db->insert([
                'cron_type'  => $row->cron_type,
                'send_array' => $row->send_array,
                'cron_error' => $error_message,
             ]);
        }

    }

}

if (isset($_GET[ 'mrr_cron_error' ])) {
    $my_res = $cron_error_db->select();

    foreach ($my_res as $row) {

        $cron_error_db->delete([ 'id' => $row->id ]);

        $crondb->insert([
            'cron_type'  => $row->cron_type,
            'send_array' => $row->send_array,
         ]);

    }

    if (absint($cron_error_db->num()) == 0) {

        $cron_error_db->empty();
    }

    exit;

}

$oni_crone_time = get_option('oni_crone_time');

if (! $oni_crone_time || intval($oni_crone_time) + 10 < time()) {

    // avin_it_cron_function();

    // update_option('oni_crone_time', time());

}
