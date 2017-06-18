<?php

namespace AppBundle\Service;

use AppBundle\Model\Task;
use AppBundle\Serializer\Groups;

class AskExport extends AskTask
{
    /**
     * {@inheritdoc}
     */
    public function execute(Task $task)
    {
        $this->producer->publish(
            $this->serializer->serialize($task, 'json', ['groups' => [Groups::EVENT_EXPORT]])
        );
    }
}
