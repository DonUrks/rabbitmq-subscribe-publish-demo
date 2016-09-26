<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$producer = $argv[1] ?? 'default';
$exchange = 'demo_messages';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// erstellt / benutzt einen exchange der zu allen customers published
$channel->exchange_declare($exchange, 'fanout', false, false, false);

$data = sprintf(
    '[%s] - message from [producer %s] in exchange [%s]',
    date('c'),
    $producer,
    $exchange
);
$channel->basic_publish(new AMQPMessage($data), $exchange);
$channel->close();
$connection->close();

echo "producer ".$producer;