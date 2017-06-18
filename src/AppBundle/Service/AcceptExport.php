<?php

namespace AppBundle\Service;

use AppBundle\Model\ExportMedia;
use AppBundle\Model\Task;
use AppBundle\Serializer\Groups;

class AcceptExport extends AcceptTask
{
    /**
     * {@inheritdoc}
     */
    public function execute(string $string): Task
    {
        /** @var ExportMedia $export */
        $export = $this->serializer->deserialize(
            $string,
            ExportMedia::class,
            'json',
            ['groups' => [Groups::EVENT_EXPORT]]
        );

        return $export;
    }
}
