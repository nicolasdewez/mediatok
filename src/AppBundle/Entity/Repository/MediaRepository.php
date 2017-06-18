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
            ->join('m.format', 'f')
            ->where('m.type IN (:types)')
            ->andWhere('t.active = TRUE')
            ->andWhere('f.active = TRUE')
            ->setParameter('types', $types)
            ->orderBy('t.title', 'ASC')
            ->addOrderBy('t.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param bool $onlyActive
     *
     * @return int
     */
    public function count(bool $onlyActive = false): int
    {
        $query = $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->join('m.type', 't')
            ->join('m.format', 'f')
        ;

        if (true === $onlyActive) {
            $query
                ->where('t.active = TRUE')
                ->andWhere('f.active = TRUE')
            ;
        }

        return $query
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
