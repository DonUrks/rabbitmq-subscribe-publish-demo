# rabbitmq-subscribe-publish-demo
A RabbitMq demo
- multiple producers
- multiple consumers
  - activated ack
  - every consumer gets every message
    - even when the consumer is offline 

## Setup
### Prerequisities
- a running rabbitmq instance on localhost (default ports). Username: guest, password: guest.
- PHP7
- composer

### Install
``composer install``

### Running the consumers
``php consumer.php one``

``php consumer.php two``

### Running the producers
``php producer.php one``

``php producer.php two``