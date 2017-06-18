<?php

namespace AppBundle\Service;

use AppBundle\Model\SearchMedia;
use AppBundle\Model\Task;
use AppBundle\Serializer\Groups;

class AcceptSearch extends AcceptTask
{
    /**
     * {@inheritdoc}
     */
    public function execute(string $string): Task
    {
        /** @var SearchMedia $search */
        $search = $this->serializer->deserialize(
            $string,
            SearchMedia::class,
            'json',
            ['groups' => [Groups::EVENT_SEARCH]]
        );

        return $search;
    }
}
