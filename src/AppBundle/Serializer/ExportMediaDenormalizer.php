<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\Type;
use AppBundle\Model\ExportMedia;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ExportMediaDenormalizer implements DenormalizerInterface
{
    /** @var DenormalizerInterface */
    private $typeDenormalizer;

    /**
     * @param DenormalizerInterface $typeDenormalizer
     */
    public function __construct(DenormalizerInterface $typeDenormalizer)
    {
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
