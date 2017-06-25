<?php

namespace AppBundle\Service\Manager;

use AppBundle\Entity\Format;
use AppBundle\Entity\Media;
use AppBundle\Entity\Type;
use AppBundle\Model\SearchMedia;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class SaverMedia
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param EntityManagerInterface $manager
     * @param LoggerInterface        $logger
     */
    public function __construct(EntityManagerInterface $manager, LoggerInterface $logger)
    {
        $this->manager = $manager;
        $this->logger = $logger;
    }

    /**
     * @param SearchMedia $searchMedia
     * @param array       $elements
     */
    public function execute(SearchMedia $searchMedia, array $elements)
    {
        $this->logger->info(sprintf('Save elements. Mode is %d', $searchMedia->getSaveMode()));

        foreach ($elements as $key => $element) {
            $media = $this->getMedia($element, $searchMedia->getSaveMode());
            if (null === $media) {
                continue;
            }

            $this->editMedia($media, $searchMedia->getType(), $searchMedia->getFormat());

            if (0 === ($key % 100)) {
                $this->manager->flush();
            }
        }

        $this->manager->flush();
    }

    /**
     * @param string $title
     * @param int    $saveMode
     *
     * @return Media|null
     */
    private function getMedia(string $title, int $saveMode): ?Media
    {
        $media = $this->manager->getRepository(Media::class)->findOneBy(['title' => $title]);

        if (null !== $media) {
            $this->logger->info(sprintf('Media %s already exists.', $title));

            // Media already exists and do nothing
            if (SearchMedia::SAVE_MODE_NOTHING === $saveMode) {
                return null;
            }

            // Media already exists and update
            return $media;
        }

        // Create new media
        $media = new Media();
        $media->setTitle($title);

        $this->manager->persist($media);

        return $media;
    }

    /**
     * @param Media  $media
     * @param Type   $type
     * @param Format $format
     */
    private function editMedia(Media $media, Type $type, Format $format)
    {
        $media
            ->setType($type)
            ->setFormat($format)
        ;
    }
}
