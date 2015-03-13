<?php

namespace LesTilleuls\DemoBundle\Component\AMPQ;

use LesTilleuls\DemoBundle\Entity\Foo;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class BarConsumer implements ConsumerInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        /** @var Foo $foo */
        $foo = unserialize($msg->body);
        $foo->setIsActive(true);
        $foo->setDescription('Foo object has been modified by reply');

        // Prepare reply
        $reply = new AMQPMessage(serialize($foo), ['correlation_id' => $msg->get('correlation_id')]);
        /** @var AMQPChannel $channel */
        $channel = $msg->get('channel');
        $channel->basic_publish($reply, 'bar', $msg->get('reply_to'));
    }
}
