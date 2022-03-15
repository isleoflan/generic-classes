<?php

declare(strict_types=1);

namespace IOL\Generic\v1\DataSource;

use Exception;
use IOL\Generic\v1\Enums\QueueType;
use JsonException;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Queue
{
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;
    private QueueType $type;

    public function __construct(QueueType $type)
    {
        $this->type = $type;

        $this->connection = new AMQPStreamConnection(
            host: Environment::get('RMQ_HOST'),
            port: Environment::get('RMQ_PORT'),
            user: Environment::get('RMQ_USER'),
            password: Environment::get('RMQ_PASSWORD')
        );

        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->type->getValue(), false, true, false, false);
    }

    public function __destruct()
    {
        try {
            $this->closeConnection();
        } catch(Exception) {}
    }

    /**
     * @throws Exception
     */
    public function closeConnection(): void
    {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * @throws JsonException
     */
    public function publishMessage(string|array $message, QueueType $type): void
    {
        if (is_array($message)) {
            $message = json_encode($message, JSON_THROW_ON_ERROR);
        }
        $AMQPMessage = new AMQPMessage($message);
        $this->channel->basic_publish($AMQPMessage, '', $type->getValue());
    }

    public function addConsumer(callable $callback, QueueType $type): void
    {
        $this->channel->basic_consume($type->getValue(), '', false, false, false, false, $callback);
    }

    /**
     * @return AbstractChannel|AMQPChannel
     */
    public function getChannel(): AMQPChannel|AbstractChannel
    {
        return $this->channel;
    }


}