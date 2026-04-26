<?php

namespace App\Repository;

use App\Traits\RepositoryTrait;
use App\Entity\CategoryActivity;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CategoryActivity>
 */
class CategoryActivityRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryActivity::class);
    }
}
