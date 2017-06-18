<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class TypeRepository extends EntityRepository
{
    /**
     * @param bool $onlyActive
     *
     * @return int
     */
    public function count(bool $onlyActive = false): int
    {
        $query = $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
        ;

        if (true === $onlyActive) {
            $query->where('t.active = TRUE');
        }

        return $query
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param bool $onlyActive
     *
     * @return array
     */
    public function getContainsMaxMedia(bool $onlyActive = false): array
    {
        $query = $this->createQueryBuilder('t')
            ->select('COUNT(m.id) AS nb')
            ->addSelect('t as type')
            ->innerJoin('t.medias', 'm')
            ->innerJoin('m.format', 'f')
            ->groupBy('t.id')
            ->having('COUNT(m.id) = MAX(m.id)')
        ;

        if (true === $onlyActive) {
            $query
                ->andWhere('f.active = TRUE')
                ->andWhere('t.active = TRUE')
            ;
        }

        return $query->getQuery()->getSingleResult();
    }
}
