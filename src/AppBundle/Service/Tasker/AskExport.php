<?php

namespace AppBundle\Service\Tasker;

use AppBundle\Model\TaskInterface;
use AppBundle\Serializer\Groups;

class AskExport extends AskTask
{
    /**
     * {@inheritdoc}
     */
    public function execute(TaskInterface $task)
    {
        $this->producer->publish(
            $this->serializer->serialize($task, 'json', ['groups' => [Groups::EVENT_EXPORT]])
        );
    }
}
