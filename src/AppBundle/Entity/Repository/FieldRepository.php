<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Field;
use AppBundle\Entity\Type;
use Doctrine\ORM\EntityRepository;

class FieldRepository extends EntityRepository
{
    /**
     * @param Type $type
     *
     * @return Field[]
     */
    public function getActiveAndSortedByType(Type $type): array
    {
        return $this->createQueryBuilder('f')
            ->join('f.types', 't')
            ->where('t.id = :idType')
            ->andWhere('t.active = TRUE')
            ->andWhere('f.active = TRUE')
            ->setParameter('idType', $type->getId())
            ->orderBy('f.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
