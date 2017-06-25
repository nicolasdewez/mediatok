<?php

namespace AppBundle\Service\Tasker;

use AppBundle\Model\ExportMedia;
use AppBundle\Model\TaskInterface;
use AppBundle\Serializer\Groups;

class AcceptExport extends AcceptTask
{
    /**
     * {@inheritdoc}
     */
    public function execute(string $string): TaskInterface
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
