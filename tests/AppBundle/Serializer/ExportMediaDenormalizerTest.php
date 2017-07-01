<?php

namespace tests\AppBundle\Serializer;

use AppBundle\Entity\Type;
use AppBundle\Model\ExportMedia;
use AppBundle\Serializer\ExportMediaDenormalizer;
use AppBundle\Serializer\TypeDenormalizer;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ExportMediaDenormalizerTest extends TestCase
{
    public function testDenormalize()
    {
        $type = new Type();

        $exportMedia = new ExportMedia();
        $exportMedia
            ->setFilter('filter')
            ->setMode('mode')
            ->setTypes([$type]);

        $repository = $this->createMock(ObjectRepository::class);
        $repository
            ->method('find')
            ->willReturn($exportMedia)
        ;

        $typeDenormalizer = $this->createMock(TypeDenormalizer::class);
        $typeDenormalizer
            ->method('supportsDenormalization')
            ->willReturnOnConsecutiveCalls(false, true)
        ;
        $typeDenormalizer
            ->method('denormalize')
            ->willReturn($type)
        ;

        $denormalizer = new ExportMediaDenormalizer($typeDenormalizer);

        $data = [
            'filter' => 'filter',
            'mode' => 'mode',
            'types' => ['type1', 'type2'],
        ];

        $this->assertEquals($exportMedia, $denormalizer->denormalize($data, ExportMedia::class));
    }

    public function testSupportsDenormalization()
    {
        $denormalizer = new ExportMediaDenormalizer(
            $this->createMock(DenormalizerInterface::class)
        );

        $this->assertTrue($denormalizer->supportsDenormalization([], ExportMedia::class));
        $this->assertFalse($denormalizer->supportsDenormalization([], self::class));
    }
}
