<?php
namespace oniclass;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Rabbitmq
{

    protected $queue_name = 'question-win';
    // protected $queue_name = 'test_queue';

    protected $exchange_name = 'quran-user-winner';

    protected $routing_key = 'question-win-route';
    protected $connection;

    public function __construct()
    {
        $host  = $_ENV[ 'RABBITDMQ_HOST' ];
        $port  = $_ENV[ 'RABBITDMQ_PORT' ];
        $user  = $_ENV[ 'RABBITDMQ_USER' ];
        $pass  = $_ENV[ 'RABBITDMQ_PASS' ];
        $vhost = '/';

        try {
            $this->connection = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
        } catch (Exception $e) {
            error_log('RabbitMQ Connection Error: ' . $e->getMessage());
            $this->connection = (object) [  ];
        }

    }

    protected function send_message_to_queue($message_body)
    {
        $connection = $this->connection;
        if (empty($connection)) {
            return false;
        }

        $channel = $connection->channel();
        $channel->exchange_declare($this->exchange_name, 'direct', false, true, false);

        $channel->queue_declare($this->queue_name, false, true, false, false);

        $msg = new AMQPMessage($message_body, [ 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT ]);
        // $channel->basic_publish($msg, '', $this->queue_name);
        // $channel->basic_publish($msg, $this->exchange_name, $this->routing_key);
        $channel->basic_publish($msg, $this->exchange_name, $this->queue_name);

        $channel->close();
        $connection->close();

        return true;

    }

    public function set($inputs)
    {

        $row_send = 0;
        foreach ($inputs[ 'game' ] as $input) {
            $message = [
                'game_id'        => null,
                'question_id'    => null,
                "description"    => $inputs[ 'description' ] ?? null,
                'direction'      => 'in',
                'game_type'      => $inputs[ 'game_type' ] ?? null,
                'chapter'        => $input[ 'chapter' ] ?? null,
                'chapter_number' => $input[ 'chapter_number' ] ?? null,
                'verse'          => $input[ 'verse' ] ?? null,
                'part'           => $input[ 'part' ] ?? null,
                'type'           => 'online',
                'score'          => $input[ 'score' ] ?? null,
                'winners'        => [ $inputs[ 'mobile' ] ],
                'created_at'     => current_time('mysql'),
             ];

            $send = $this->send_message_to_queue(json_encode($message));
            if ($send) {
                $row_send++;
            }
        }

        return $row_send;

    }

}
