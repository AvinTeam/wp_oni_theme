<?php

use oniclass\ONIDB;

(defined('ABSPATH')) || exit;

// افزودن اکشن کرون
//add_action('avin_it_cron_job', 'avin_it_cron_function');

function avin_it_cron_function()
{
    $api_url = ' http://94.232.173.178:31963/api/rabbitMq';

    $crondb        = new ONIDB('cron');
    $cron_error_db = new ONIDB('cron_error');

    $my_res = $crondb->select([ 'per_page' => 50 ]);

    foreach ($my_res as $row) {

        $error_message = '';

        $tracking = (absint($row->tracking) + 1);

        $data  = [ 'tracking' => $tracking ];
        $where = [ 'id' => $row->id ];
        $crondb->update($data, $where);

        $response = wp_remote_post(
            $api_url,
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
                'match_id'   => $row->match_id,
                'send_array' => $row->send_array,
                'cron_error' => $error_message,
             ]);
        }

    }

}

$oni_crone_time = get_option('oni_crone_time');

if ( !$oni_crone_time ||  intval($oni_crone_time) + 10 < time()) {

    avin_it_cron_function();

    update_option('oni_crone_time', time());

}
