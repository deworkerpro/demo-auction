<?php

declare(strict_types=1);

namespace App\Queue;

use Closure;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

final readonly class AmqpQueue implements Publisher, Consumer
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

    public function consume(string $queue, Closure $callback): void
    {
        $channel = $this->connection->channel();

        $channel->basic_consume(
            queue: $queue,
            consumer_tag: 'consumer_' . getmypid(),
            callback: static function (AMQPMessage $message) use ($channel, $callback): void {
                /**
                 * @var array{
                 *     type: string,
                 *     payload: string
                 * } $body
                 */
                $body = json_decode($message->getBody(), true, 512, JSON_THROW_ON_ERROR);

                /** @var array{message_id: string} $properties */
                $properties = $message->get_properties();

                $callback(new Message(
                    id: (int)$properties['message_id'],
                    type: $body['type'],
                    payload: (array)json_decode($body['payload'], true)
                ));

                $channel->basic_ack($message->getDeliveryTag());
            }
        );

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}
