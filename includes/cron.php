<?php

use oniclass\ONIDB;

(defined('ABSPATH')) || exit;

// افزودن اکشن کرون
add_action('avin_it_cron_job', 'avin_it_cron_function');

function avin_it_cron_function()
{

    notificator('foreach', 'start');

    $crondb        = new ONIDB('cron');
    $cron_error_db = new ONIDB('cron_error');

    $my_res = $crondb->select([ 'per_page' => 50 ]);

    foreach ($my_res as $row) {

        $error_message = '';

        $get_error = 0;

        $tracking = (absint($row->tracking) + 1);

        $data  = [ 'tracking' => $tracking ];
        $where = [ 'id' => $row->id ];
        $crondb->update($data, $where);

        $api_url = 'http://172.16.131.33:68/api/rabbitMq';

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

            $get_error = 1;

            $error_message = $response->get_error_message();

        } else {

            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body);

            if (isset($data->success) && $data->success) {

                $crondb->delete([ 'id' => $row->id ]);

            } else {
                $get_error = 1;

                $error_message = $data->message;

            }

            if ($get_error == 1 && $tracking > 5) {

                $crondb->delete([ 'id' => $row->id ]);

                $cron_error_db->insert([
                    'match_id'   => $row->match_id,
                    'send_array' => $row->send_array,
                    'cron_error' => $error_message,
                 ]);
            }

        }

    }

}
