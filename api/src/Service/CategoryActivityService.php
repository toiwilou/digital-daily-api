<?php

namespace App\Service;

use App\Entity\CategoryActivity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryActivityRepository;

class CategoryActivityService
{
    private $repository;
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        CategoryActivityRepository $categoryActivityRepository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $categoryActivityRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persist(Request $request, CategoryActivity $category): void
    {
        $data = json_decode($request->getContent(), true);

        $category
            ->setName($data['name'] ?? $category->getName())
            ->setActive(!!$data['active'] ?? $category->isActive())
        ;

        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new CategoryActivity());
    }

    public function edit(Request $request, CategoryActivity $category): void
    {
        $this->persist($request, $category);
    }
}
