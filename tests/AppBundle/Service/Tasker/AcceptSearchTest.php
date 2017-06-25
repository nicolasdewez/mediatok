<?php

namespace tests\AppBundle\Service\Tasker;

use AppBundle\Model\SearchMedia;
use AppBundle\Service\Tasker\AcceptSearch;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;

class AcceptSearchTest extends TestCase
{
    public function testExecute()
    {
        $task = new SearchMedia();
        $task
            ->setFilter('.*')
            ->setDirectory('/')
            ->setFileMode(SearchMedia::FILE_MODE_DIRECTORIES)
        ;

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer
            ->method('deserialize')
            ->willReturn($task)
        ;

        $object = new AcceptSearch($serializer);
        $this->assertSame($task, $object->execute(''));
    }
}
