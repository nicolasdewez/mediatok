<?php

namespace AppBundle\Service\Tasker;

use AppBundle\Model\TaskInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AcceptTask
{
    /** @var SerializerInterface */
    protected $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param string $string
     *
     * @return TaskInterface
     */
    abstract public function execute(string $string): TaskInterface;
}
