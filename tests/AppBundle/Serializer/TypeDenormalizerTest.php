<?php

namespace tests\AppBundle\Serializer;

use AppBundle\Entity\Type;
use AppBundle\Serializer\TypeDenormalizer;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class TypeDenormalizerTest extends TestCase
{
    public function testDenormalize()
    {
        $type = new Type();

        $repository = $this->createMock(ObjectRepository::class);
        $repository
            ->method('find')
            ->willReturn($type)
        ;

        $manager = $this->createMock(EntityManagerInterface::class);
        $manager
            ->method('getRepository')
            ->willReturn($repository)
        ;

        $denormalizer = new TypeDenormalizer($manager);
        $this->assertSame($type, $denormalizer->denormalize(['id' => 1], Type::class));
    }

    public function testSupportsDenormalization()
    {
        $denormalizer = new TypeDenormalizer(
            $this->createMock(EntityManagerInterface::class)
        );

        $this->assertTrue($denormalizer->supportsDenormalization(['id' => 1], Type::class));
        $this->assertFalse($denormalizer->supportsDenormalization(['id' => 1], self::class));
        $this->assertFalse($denormalizer->supportsDenormalization([], Type::class));
        $this->assertFalse($denormalizer->supportsDenormalization([], self::class));
    }
}
