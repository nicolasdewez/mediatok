<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\Format;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class FormatDenormalizer implements DenormalizerInterface
{
    /** @var EntityManagerInterface */
    private $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return $this->manager->getRepository(Format::class)->find($data['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Format::class && isset($data['id']);
    }
}
