<?php

namespace AppBundle\Service;

use AppBundle\Model\Task;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AskTask
{
    /** @var ProducerInterface */
    protected $producer;

    /** @var SerializerInterface */
    protected $serializer;

    /**
     * @param ProducerInterface   $producer
     * @param SerializerInterface $serializer
     */
    public function __construct(ProducerInterface $producer, SerializerInterface $serializer)
    {
        $this->producer = $producer;
        $this->serializer = $serializer;
    }

    /**
     * @param Task $task
     */
    abstract public function execute(Task $task);
}
