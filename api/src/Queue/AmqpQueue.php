<?php

declare(strict_types=1);

namespace App\Queue;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

final readonly class AmqpQueue implements Publisher
{
    public function __construct(private AMQPStreamConnection $connection) {}

    public function publish(string $exchange, Message $message): void
    {
        $channel = $this->connection->channel();

        $body = json_encode([
            'type' => $message->type,
            'payload' => json_encode($message->payload),
        ], JSON_THROW_ON_ERROR);

        $channel->basic_publish(new AMQPMessage(
            $body,
            [
                'content_type' => 'text/plain',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'message_id' => $message->id,
                'timestamp' => time(),
            ]
        ), $exchange);
    }
}
