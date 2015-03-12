<?php

namespace LesTilleuls\DemoBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FooFormHandler
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Producer
     *
     * @var Producer
     */
    protected $producer;

    /**
     * @param EntityManager $entityManager
     * @param Producer $producer
     */
    public function __construct(EntityManager $entityManager, Producer $producer)
    {
        $this->entityManager = $entityManager;
        $this->producer = $producer;
    }

    /**
     * @param  FormInterface       $form
     * @param  Request             $request
     * @return mixed|FormInterface
     */
    public function handle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity = $form->getData();

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            $this->producer->publish(serialize($entity));

            return $entity;
        }

        return $form;
    }
}
