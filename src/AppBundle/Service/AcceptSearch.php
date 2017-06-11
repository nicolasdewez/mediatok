<?php

namespace AppBundle\Service;

use AppBundle\Model\SearchMedia;
use AppBundle\Serializer\Groups;
use Symfony\Component\Serializer\SerializerInterface;

class AcceptSearch
{
    /** @var SerializerInterface */
    private $serializer;

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
     * @return SearchMedia
     */
    public function execute(string $string): SearchMedia
    {
        /** @var SearchMedia $search */
        $search = $this->serializer->deserialize(
            $string,
            'AppBundle\Model\SearchMedia',
            'json',
            ['groups' => [Groups::EVENT_SEARCH]]
        );

        return $search;
    }
}
