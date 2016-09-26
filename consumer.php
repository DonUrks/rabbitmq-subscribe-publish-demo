<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Wire\AMQPTable;

$consumer = $argv[1] ?? 'default';
$exchange = 'demo_messages';
$queue = 'demo_messages_queue_'.$consumer;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

$channel = $connection->channel();
// erstellt / benutzt einen exchange der zu allen customers published
$channel->exchange_declare($exchange, 'fanout', false, false, false);

$channel->queue_declare($queue, false, false, false, false, false, new AMQPTable(array(
    "x-dead-letter-exchange" => "dead_letter_exchange",
    "x-message-ttl" => 5000,
    //"x-expires" => 16000
)));
$channel->queue_bind($queue, 'demo_messages');

echo '-- [consumer '.$consumer.'] waiting for messages in queue ['.$queue.'] --', "\n";

$callback = function($msg) use($channel) {
    echo $msg->body, "\n";

    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_consume($queue, 'consumer'.$consumer, false, false, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();