<?php

namespace AppBundle\Service;

use AppBundle\Model\SearchMedia;
use AppBundle\Serializer\Groups;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AskSearch
{
    /** @var ProducerInterface */
    private $producer;

    /** @var SerializerInterface */
    private $serializer;

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
     * @param SearchMedia $model
     */
    public function execute(SearchMedia $model)
    {
        $this->producer->publish(
            $this->serializer->serialize($model, 'json', ['groups' => [Groups::EVENT_SEARCH]])
        );
    }
}
