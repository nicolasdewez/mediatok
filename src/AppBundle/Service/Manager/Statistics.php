<?php

namespace AppBundle\Service\Manager;

use AppBundle\Entity\Format;
use AppBundle\Entity\Media;
use AppBundle\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;

class Statistics
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var ListExports */
    private $listExports;

    /**
     * @param EntityManagerInterface $manager
     * @param ListExports            $listExports
     */
    public function __construct(EntityManagerInterface $manager, ListExports $listExports)
    {
        $this->manager = $manager;
        $this->listExports = $listExports;
    }

    /**
     * @return array
     */
    public function execute(): array
    {
        $stats = [
            'exports' => $this->listExports->countByExtension(),
            'types' => [
                'all' => $this->manager->getRepository(Type::class)->count(),
                'active' => $this->manager->getRepository(Type::class)->count(true),
                'maxMedia' => $this->manager->getRepository(Type::class)->getContainsMaxMedia(),
                'maxMediaActive' => $this->manager->getRepository(Type::class)->getContainsMaxMedia(true),
            ],
            'formats' => [
                'all' => $this->manager->getRepository(Format::class)->count(),
                'active' => $this->manager->getRepository(Format::class)->count(true),
                'maxMedia' => $this->manager->getRepository(Format::class)->getContainsMaxMedia(),
                'maxMediaActive' => $this->manager->getRepository(Format::class)->getContainsMaxMedia(true),
            ],
            'medias' => [
                'all' => $this->manager->getRepository(Media::class)->count(),
                'active' => $this->manager->getRepository(Media::class)->count(true),
            ],
        ];

        return $stats;
    }
}
