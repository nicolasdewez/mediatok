<?php

namespace tests\AppBundle\Service\Manager;

use AppBundle\Entity\Format;
use AppBundle\Entity\Repository\FormatRepository;
use AppBundle\Entity\Repository\MediaRepository;
use AppBundle\Entity\Repository\TypeRepository;
use AppBundle\Entity\Type;
use AppBundle\Service\Manager\ListExports;
use AppBundle\Service\Manager\Statistics;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class StatisticsTest extends TestCase
{
    public function testExecute()
    {
        $type = new Type();
        $format = new Format();

        // List
        $listExports = $this->createMock(ListExports::class);
        $listExports
            ->method('countByExtension')
            ->willReturn(['csv' => 2, 'pdf' => 1])
        ;

        // Repositories
        $typeRepository = $this->createMock(TypeRepository::class);
        $typeRepository
            ->method('count')
            ->willReturnOnConsecutiveCalls(10, 9)
        ;
        $typeRepository
            ->method('getContainsMaxMedia')
            ->willReturnOnConsecutiveCalls(['type' => $type, 'nb' => 4], ['type' => $type, 'nb' => 3])
        ;

        $formatRepository = $this->createMock(FormatRepository::class);
        $formatRepository
            ->method('count')
            ->willReturnOnConsecutiveCalls(8, 7)
        ;
        $formatRepository
            ->method('getContainsMaxMedia')
            ->willReturnOnConsecutiveCalls(['format' => $format, 'nb' => 2], ['format' => $format, 'nb' => 1])
        ;

        $mediaRepository = $this->createMock(MediaRepository::class);
        $mediaRepository
            ->method('count')
            ->willReturnOnConsecutiveCalls(6, 5)
        ;

        // Manager
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager
            ->method('getRepository')
            ->willReturnOnConsecutiveCalls(
                $typeRepository,
                $typeRepository,
                $typeRepository,
                $typeRepository,
                $formatRepository,
                $formatRepository,
                $formatRepository,
                $formatRepository,
                $mediaRepository,
                $mediaRepository
            )
        ;

        $statistics = new Statistics($manager, $listExports);

        $expected = [
            'exports' => ['csv' => 2, 'pdf' => 1],
            'types' => [
                'all' => 10,
                'active' => 9,
                'maxMedia' => ['type' => $type, 'nb' => 4],
                'maxMediaActive' => ['type' => $type, 'nb' => 3],
            ],
            'formats' => [
                'all' => 8,
                'active' => 7,
                'maxMedia' => ['format' => $format, 'nb' => 2],
                'maxMediaActive' => ['format' => $format, 'nb' => 1],
            ],
            'medias' => [
                'all' => 6,
                'active' => 5,
            ],
        ];

        $this->assertSame($expected, $statistics->execute());
    }
}
