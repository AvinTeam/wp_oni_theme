<?php
namespace oniclass;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPException;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQHandler
{
    private $host;
    private $port;
    private $user;
    private $pass;
    private $vhost;
    private $connection;
    private $channel;

    public function __construct()
    {
        $this->host  = $_ENV[ 'RABBITDMQ_HOST' ];
        $this->port  = $_ENV[ 'RABBITDMQ_PORT' ];
        $this->user  = $_ENV[ 'RABBITDMQ_USER' ];
        $this->pass  = $_ENV[ 'RABBITDMQ_PASS' ];
        $this->vhost = $_ENV[ 'RABBITDMQ_VHOST' ];
    }

    public function connect()
    {
        try {
            $this->connection = new AMQPStreamConnection(
                $this->host,
                $this->port,
                $this->user,
                $this->pass,
                $this->vhost
            );
            $this->channel = $this->connection->channel();
        } catch (AMQPException $e) {
            throw new Exception("Failed to connect to RabbitMQ: " . $e->getMessage());
        }
    }

    public function sendMessage($message)
    {
        $exchange   = 'quran-user-winner';
        $queue      = 'question-win';
        $routingKey = 'question-win-route';

        try {
            $this->channel->exchange_declare($exchange, 'direct', false, true, false);
            $this->channel->queue_declare($queue, false, true, false, false);
            $this->channel->queue_bind($queue, $exchange, $routingKey);

            $msg = new AMQPMessage(json_encode($message));
            $this->channel->basic_publish($msg, $exchange, $routingKey);

            return true;
        } catch (AMQPException $e) {
            throw new Exception("Failed to send message: " . $e->getMessage());
        }
    }

    public function close()
    {
        try {
            if ($this->channel) {
                $this->channel->close();
            }
            if ($this->connection) {
                $this->connection->close();
            }
        } catch (AMQPException $e) {
            throw new Exception("Failed to close connection: " . $e->getMessage());
        }
    }
}