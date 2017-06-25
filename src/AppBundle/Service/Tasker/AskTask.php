<?php

namespace AppBundle\Service\Tasker;

use AppBundle\Model\TaskInterface;
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
     * @param TaskInterface $task
     */
    abstract public function execute(TaskInterface $task);
}
