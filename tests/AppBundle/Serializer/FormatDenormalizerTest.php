<?php

namespace tests\AppBundle\Serializer;

use AppBundle\Entity\Format;
use AppBundle\Serializer\FormatDenormalizer;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class FormatDenormalizerTest extends TestCase
{
    public function testDenormalize()
    {
        $format = new Format();

        $repository = $this->createMock(ObjectRepository::class);
        $repository
            ->method('find')
            ->willReturn($format)
        ;

        $manager = $this->createMock(EntityManagerInterface::class);
        $manager
            ->method('getRepository')
            ->willReturn($repository)
        ;

        $denormalizer = new FormatDenormalizer($manager);
        $this->assertSame($format, $denormalizer->denormalize(['id' => 1], Format::class));
    }

    public function testSupportsDenormalization()
    {
        $denormalizer = new FormatDenormalizer(
            $this->createMock(EntityManagerInterface::class)
        );

        $this->assertTrue($denormalizer->supportsDenormalization(['id' => 1], Format::class));
        $this->assertFalse($denormalizer->supportsDenormalization(['id' => 1], self::class));
        $this->assertFalse($denormalizer->supportsDenormalization([], Format::class));
        $this->assertFalse($denormalizer->supportsDenormalization([], self::class));
    }
}
