<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\Type;
use AppBundle\Model\ExportMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ExportMediaDenormalizer implements DenormalizerInterface
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var DenormalizerInterface */
    private $typeDenormalizer;

    /**
     * @param EntityManagerInterface $manager
     * @param DenormalizerInterface  $typeDenormalizer
     */
    public function __construct(EntityManagerInterface $manager, DenormalizerInterface $typeDenormalizer)
    {
        $this->manager = $manager;
        $this->typeDenormalizer = $typeDenormalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $types = [];
        foreach ($data['types'] as $type) {
            if (!$this->typeDenormalizer->supportsDenormalization($type, Type::class, $format)) {
                continue;
            }

            $types[] = $this->typeDenormalizer->denormalize($type, Type::class);
        }

        $exportMedia = new ExportMedia();
        $exportMedia
            ->setFilter($data['filter'])
            ->setMode($data['mode'])
            ->setTypes($types)
        ;

        return $exportMedia;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === ExportMedia::class;
    }
}
