<?php

namespace tests\AppBundle\Service;

use AppBundle\Model\ExportMedia;
use AppBundle\Service\AcceptExport;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;

class AcceptExportTest extends TestCase
{
    public function testExecute()
    {
        $task = new ExportMedia();
        $task
            ->setMode(ExportMedia::MODE_CSV)
            ->setFilter('.*')
            ->setTypes([])
        ;

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer
            ->method('deserialize')
            ->willReturn($task)
        ;

        $object = new AcceptExport($serializer);
        $this->assertSame($task, $object->execute(''));
    }
}
