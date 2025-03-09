<?php
namespace oniclass;

use Bunny\Channel;
use Bunny\Client;
use Exception;

class Rabbitmq
{
    protected $queue_name    = 'question-win';       // نام صف
    protected $exchange_name = 'quran-user-winner';  // Exchange (اختیاری)
    protected $routing_key   = 'question-win-route'; // Routing Key
    protected $client;
    protected $channel;

    public function __construct()
    {
        $host  = $_ENV[ 'RABBITDMQ_HOST' ];
        $port  = $_ENV[ 'RABBITDMQ_PORT' ];
        $user  = $_ENV[ 'RABBITDMQ_USER' ];
        $pass  = $_ENV[ 'RABBITDMQ_PASS' ];
        $vhost = $_ENV[ 'RABBITDMQ_VHOST' ];

        try {
            // ایجاد اتصال به RabbitMQ
            $this->client = new Client([
                'host'  => $host,
                'port'  => $port,
                'user'  => $user,
                'pass'  => $pass,
                'vhost' => $vhost,
                'tls'   => ($port === 5671), // اگر از TLS استفاده می‌کنید
             ]);

            // اتصال به RabbitMQ
            $this->client->connect();

            // ایجاد کانال
            $this->channel = $this->client->channel();
        } catch (Exception $e) {
            error_log('RabbitMQ Connection Error: ' . $e->getMessage());
            $this->client  = null;
            $this->channel = null;
        }
    }

    /**
     * ارسال پیام به صف
     *
     * @param string $message_body بدنه پیام
     * @return bool|string true در صورت موفقیت، پیام خطا در صورت شکست
     */
    public function send_message_to_queue($message_body)
    {
        if (! $this->channel) {
            return 'خطا: اتصال به RabbitMQ برقرار نشد.';
        }

        try {
            // تعریف صف (اگر وجود نداشته باشد، ایجاد می‌شود)
            $this->channel->queueDeclare($this->queue_name, false, true, false, false);

            // ارسال پیام
            $this->channel->publish(
                $message_body,        // بدنه پیام
                [  ],                 // هدرها (اختیاری)
                $this->exchange_name, // Exchange (اختیاری)
                $this->routing_key    // Routing Key
            );

            return true; // ارسال موفقیت‌آمیز
        } catch (Exception $e) {
            error_log('RabbitMQ Publish Error: ' . $e->getMessage());
            return 'خطا در ارسال پیام به RabbitMQ: ' . $e->getMessage();
        }
    }

    /**
     * بررسی اتصال به RabbitMQ
     *
     * @return bool true اگر اتصال برقرار باشد، در غیر این صورت false
     */
    public function isConnected()
    {
        return $this->channel !== null;
    }

    public function __destruct()
    {
        try {
            if ($this->channel) {
                $this->channel->close();
            }
            if ($this->client) {
                $this->client->disconnect();
            }
        } catch (Exception $e) {
            error_log('RabbitMQ Close Error: ' . $e->getMessage());
        }
    }
}