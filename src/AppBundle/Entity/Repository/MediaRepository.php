<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Media;
use AppBundle\Entity\Type;
use Doctrine\ORM\EntityRepository;

class MediaRepository extends EntityRepository
{
    /**
     * @param Type[] $types
     *
     * @return Media[]
     */
    public function getByTypes(array $types): array
    {
        return $this->createQueryBuilder('m')
            ->join('m.type', 't')
            ->where('m.type IN (:types)')
            ->setParameter('types', $types)
            ->orderBy('t.title', 'ASC')
            ->addOrderBy('t.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
