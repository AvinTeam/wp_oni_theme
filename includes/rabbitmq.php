<?php

use oniclass\Rabbitmq;

if (isset($_GET[ 'rab_test' ])) {

    $inputs = [
        'mobile'      => '09383149343',
        'description' => 'id game 52',
        'game'        => [
            [
                'score'          => 30,
                'chapter'        => 'احزاب',
                'chapter_number' => 33,
                'verse'          => 23,
                'type'           => 'تبیین',

             ],
         ],

     ];

    $rabbitmq = new Rabbitmq;

    $testq = $rabbitmq->set($inputs);

    print_r($testq);

    echo '<br>';




    $inputs = [
        'mobile'      => '09113078966',
        'description' => 'id game 52',
        'game'        => [
            [
                'score'          => 50,
                'chapter'        => 'احزاب',
                'chapter_number' => 33,
                'verse'          => 23,
                'type'           => 'تبیین',

             ],
         ],

     ];

    $api_url = ' http://94.232.173.178:31963/api/';

    $response = wp_remote_post(
        $api_url . 'rabbitMq',
        [
            'timeout' => 1000,
            'headers' => [
                'Authorization' => 'Bearer ' . ONI_TOKEN, // ارسال توکن در هدر
                'Content-Type'  => 'application/json',    // نوع محتوای بدنه
             ],
            'body'    => json_encode($inputs),
         ]);

    if (is_wp_error($response)) {

        $error_message = $response->get_error_message();
        var_dump($error_message);

        echo '<br>';

    } else {

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);

        if (isset($data->success) && $data->success) {
            var_dump($data);

            echo '<br>';

        } else {

            $error_message = $data->message;

            print_r($error_message);

            echo '<br>';

        }

    }








    
    exit;
}

