<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class FormatRepository extends EntityRepository
{
    /**
     * @param bool $onlyActive
     *
     * @return int
     */
    public function count(bool $onlyActive = false): int
    {
        $query = $this->createQueryBuilder('f')
            ->select('COUNT(f.id)')
        ;

        if (true === $onlyActive) {
            $query->where('f.active = TRUE');
        }

        return $query
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param bool $onlyActive
     *
     * @return array|null
     */
    public function getContainsMaxMedia(bool $onlyActive = false): ?array
    {
        $query = $this->createQueryBuilder('f')
            ->select('COUNT(m.id) AS nb')
            ->addSelect('f as format')
            ->innerJoin('f.medias', 'm')
            ->innerJoin('m.type', 't')
            ->groupBy('f.id')
            ->having('COUNT(m.id) = MAX(m.id)')
        ;

        if (true === $onlyActive) {
            $query
                ->andWhere('f.active = TRUE')
                ->andWhere('t.active = TRUE')
            ;
        }

        $results = $query->getQuery()->getResult();
        if (0 === count($results)) {
            return null;
        }

        return $results[0];
    }
}
