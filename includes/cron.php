<?php

use oniclass\ONIDB;
use oniclass\RabbitMQHandler;

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

    $array_is_ok   = [  ];
    $array_is_nook = [  ];

    $crondb        = new ONIDB('cron');
    $cron_error_db = new ONIDB('cron_error');

    $my_res = $crondb->select([
        'per_page' => 3000,
        'order_by' => [
            'id',
            'DESC',
         ],
        'data'     => [
            'cron_type' => 'game',
         ],
     ]);

    try {
        $rabbitMQHandler = new RabbitMQHandler();
        $rabbitMQHandler->connect();

        foreach ($my_res as $i => $row) {

            $error_message = '';

            $tracking = (absint($row->tracking) + 1);

            $data  = [ 'tracking' => $tracking ];
            $where = [ 'id' => $row->id ];
            $crondb->update($data, $where);

            $inputs = unserialize($row->send_array);

            try {

                foreach ($inputs[ 'game' ] as $index => $input) {
                    $message = [
                        'game_id'        => null,
                        'question_id'    => null,
                        'description'    => $inputs[ 'description' ] ?? null,
                        'direction'      => 'in',
                        'game_type'      => $inputs[ 'game_type' ] ?? null,
                        'chapter'        => $input[ 'chapter' ] ?? null,
                        'chapter_number' => $input[ 'chapter_number' ] ?? null,
                        'verse'          => $input[ 'verse' ] ?? null,
                        'part'           => $input[ 'part' ] ?? null,
                        'type'           => $input[ 'type' ] ?? null,
                        'score'          => $input[ 'score' ] ?? null,
                        'winners'        => [ $inputs[ 'mobile' ] ],
                        'created_at'     => current_time('mysql'),
                     ];

                    try {
                        $rabbitMQHandler->sendMessage($message);

                        $array_is_ok[ 'p' . $i ][ 'ok-' . $index ] = $index;
                        $crondb->delete([ 'id' => $row->id ]);

                    } catch (Exception $e) {

                        $error_message                      = $e->getMessage();
                        $array_is_nook[ 'p' . $i ][ 'no1' ] = $array_is_nook;

                    }

                }

            } catch (Exception $e) {

                $array_is_nook = $e->getMessage();

                $array_is_nook[ 'p' . $i ][ 'no2' ] = $array_is_nook;
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

        $rabbitMQHandler->close();

    } catch (Exception $e) {

        $error_message          = $e->getMessage();
        $array_is_nook[ 'no3' ] = $array_is_nook;

    }

    return [
        'array_is_ok'   => count($array_is_ok),
        'array_is_nook' => count($array_is_nook),
     ];

}

function mrr_cron_function()
{

    $array_is_ok   = [  ];
    $array_is_nook = [  ];

    $crondb        = new ONIDB('cron');
    $cron_error_db = new ONIDB('cron_error');

    $my_res = $crondb->select([
        'per_page' => 3000,
        'data'     => [
            'cron_type' => 'game',
         ],
     ]);

    try {
        $rabbitMQHandler = new RabbitMQHandler();
        $rabbitMQHandler->connect();

        foreach ($my_res as $i => $row) {

            $error_message = '';

            $tracking = (absint($row->tracking) + 1);

            $data  = [ 'tracking' => $tracking ];
            $where = [ 'id' => $row->id ];
            $crondb->update($data, $where);

            $inputs = unserialize($row->send_array);

            try {

                foreach ($inputs[ 'game' ] as $index => $input) {
                    $message = [
                        'game_id'        => null,
                        'question_id'    => null,
                        'description'    => $inputs[ 'description' ] ?? null,
                        'direction'      => 'in',
                        'game_type'      => $inputs[ 'game_type' ] ?? null,
                        'chapter'        => $input[ 'chapter' ] ?? null,
                        'chapter_number' => $input[ 'chapter_number' ] ?? null,
                        'verse'          => $input[ 'verse' ] ?? null,
                        'part'           => $input[ 'part' ] ?? null,
                        'type'           => $input[ 'type' ] ?? null,
                        'score'          => $input[ 'score' ] ?? null,
                        'winners'        => [ $inputs[ 'mobile' ] ],
                        'created_at'     => current_time('mysql'),
                     ];

                    try {
                        $rabbitMQHandler->sendMessage($message);

                        $array_is_ok[ 'p' . $i ][ 'ok-' . $index ] = $index;
                        $crondb->delete([ 'id' => $row->id ]);

                    } catch (Exception $e) {

                        $error_message                      = $e->getMessage();
                        $array_is_nook[ 'p' . $i ][ 'no1' ] = $array_is_nook;

                    }

                }

            } catch (Exception $e) {

                $array_is_nook = $e->getMessage();

                $array_is_nook[ 'p' . $i ][ 'no2' ] = $array_is_nook;
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

        $rabbitMQHandler->close();

    } catch (Exception $e) {

        $error_message          = $e->getMessage();
        $array_is_nook[ 'no3' ] = $array_is_nook;

    }

    return [
        'array_is_ok'   => count($array_is_ok),
        'array_is_nook' => count($array_is_nook),
     ];

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

if (isset($_GET[ 'cron_rabbit_mq' ])) {

    $current_time   = current_time('timestamp');
    $current_minute = date('i', $current_time);

    avin_it_cron_function();

    if ($current_minute >= 20 && $current_minute < 50) {

        //avin_it_cron_function();

    }

}

if (isset($_GET[ 'cron_rabbit' ])) {

    mrr_cron_function();
}

if (isset($_GET[ 'rab_test' ])) {

    echo "<p> total_cron       :" . number_format(absint($crondb->num())) . "</p>";
    echo "<p> total_cron_error :" . number_format(absint($cron_error_db->num())) . "</p>";
    echo "<p> time             :" .date('H:i:s') . "</p>";

    exit;
}