<?php 

namespace App\Traits;

trait RepositoryTrait
{
    public function getAll(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.active = :value')
            ->setParameter('value', true)
            ->getQuery()
            ->getResult()
        ;
    }
}
