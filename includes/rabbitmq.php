<?php

use oniclass\Rabbitmq;

if (isset($_GET[ 'rab_test' ])) {

    // echo '0<br>';

    // $inputs = [
    //     'mobile'      => '09113078966',
    //     'description' => 'id game 53',
    //     'game_type'   => 'online',
    //     'game'        => [
    //         [
    //             'score'          => 1,
    //             'chapter'        => 'احزاب',
    //             'chapter_number' => 33,
    //             'verse'          => 23,
    //             'type'           => 'تبیین',

    //          ],
    //      ],

    //  ];

    // $api_url = ' http://94.232.173.178:31963/api/';

    // $response = wp_remote_post(
    //     $api_url . 'rabbitMq',
    //     [
    //         'timeout' => 1000,
    //         'headers' => [
    //             'Authorization' => 'Bearer ' . ONI_TOKEN, // ارسال توکن در هدر
    //             'Content-Type'  => 'application/json',    // نوع محتوای بدنه
    //          ],
    //         'body'    => json_encode($inputs),
    //      ]);

    // if (is_wp_error($response)) {

    //     $error_message = $response->get_error_message();
    //     var_dump($error_message);

    //     echo '<br>';

    // } else {

    //     $body = wp_remote_retrieve_body($response);
    //     print_r($body);
    //     echo '<br>';

    //     $data = json_decode($body);

    //     if (isset($data->success) && $data->success) {
    //         var_dump($data);

    //         echo '<br>';

    //     } else {

    //         $error_message = $data->message;

    //         print_r($error_message);

    //         echo '<br>';

    //     }

    // }

    // echo '<br>';

// ایجاد یک نمونه از کلاس Rabbitmq
    $rabbitmq = new Rabbitmq();

// بررسی اتصال
    if (! $rabbitmq->isConnected()) {
        die("اتصال به RabbitMQ ناموفق بود.\n");
    }

    $inputs = [
        'mobile'      => '09372573489',
        'description' => 'id game 55',
        'game_type'   => 'online',
        'game'        => [
            [
                'score'          => 5,
                'chapter'        => 'زمر',
                'chapter_number' => 39,
                'verse'          => 18,
                'type'           => 'تبیین',

             ],
         ],

     ];

    $row_res = 0;

    foreach ($inputs[ 'game' ] as $input) {
        $message = (object) [
            'game_id'        => null,
            'question_id'    => null,
            "description"    => $inputs[ 'description' ] ?? null,
            'direction'      => 'in',
            'game_type'      => $inputs[ 'game_type' ] ?? null,
            'chapter'        => $input[ 'chapter' ] ?? null,
            'chapter_number' => $input[ 'chapter_number' ] ?? null,
            'verse'          => $input[ 'verse' ] ?? null,
            'part'           => $input[ 'part' ] ?? null,
            'type'           => $input[ 'type' ] ?? null,
            'score'          => $input[ 'score' ] ?? null,
            'winners'        => [ $inputs[ 'mobile' ] ], // Convert Collection to array
            'created_at'     => current_time('mysql'),
         ];


        print_r($message);
        echo '<br>';
        echo gettype($message);
        echo '<br>';

        $result = $rabbitmq->send_message_to_queue(json_encode($inputs));

        if ($result === true) {
            echo "پیام با موفقیت ارسال شد.\n";
            $row_res++;
        } else {
            echo $result; // نمایش پیام خطا
        }

        echo '<br>';
    }

    echo $row_res;

    exit;
}
