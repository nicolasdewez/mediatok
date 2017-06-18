<?php

namespace AppBundle\Service;

use AppBundle\Model\Task;
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
     * @return Task
     */
    abstract public function execute(string $string): Task;
}
