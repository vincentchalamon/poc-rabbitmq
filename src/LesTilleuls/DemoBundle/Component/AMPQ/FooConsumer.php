<?php

namespace LesTilleuls\DemoBundle\Component\AMPQ;

use LesTilleuls\DemoBundle\Entity\Foo;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class FooConsumer implements ConsumerInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        /** @var Foo $foo */
        $foo = unserialize($msg->body);
        echo "foo ".$foo->getName()." successfully downloaded!\n";
    }
}
