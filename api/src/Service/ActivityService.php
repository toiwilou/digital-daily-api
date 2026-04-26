<?php

namespace App\Service;

use App\Entity\Activity;
use App\Entity\CategoryActivity;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryActivityRepository;

class ActivityService
{
    private $repository;
    private $entityManager;
    private $categoryActivityRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ActivityRepository $activityRepository,
        CategoryActivityRepository $categoryActivityRepository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $activityRepository;
        $this->categoryActivityRepository = $categoryActivityRepository;
    }

    public function getCategories(): array
    {
        return $this->categoryActivityRepository->getAll();
    }

    public function createAll(): void
    {
        $datas = json_decode(file_get_contents(__DIR__ . '/../../json/activities.json'), true);
        
        foreach ($datas as $data) {
            $activity = $this->repository->findOneBy(['name' => $data['name']]);

            if (!$activity)
            {
                $category = $this->categoryActivityRepository->findOneBy(['name' => $data['category']]);
                
                if (!$category) {
                    $category = new CategoryActivity();
                    $category->setName($data['category'])->setActive(true);

                    $this->entityManager->persist($category);
                }

                $activity = new Activity();
                $activity
                    ->setName($data['name'])
                    ->setCategory($category)
                    ->setIcon($data['icon'])
                    ->setPicture($data['picture'])
                    ->setActive(true)
                ;

                $this->entityManager->persist($activity);
                $this->entityManager->flush();
            }
        }
    }

    public function persist(Request $request, Activity $activity): void
    {
        $data = json_decode($request->getContent(), true);
        $category = $this->categoryActivityRepository->findOneBy(['id' => (int) $data['category']]);

        $activity
            ->setName($data['name'] ?? $activity->getName())
            ->setCategory($category ?? $activity->getCategory())
            ->setActive(!!$data['active'] ?? $activity->isActive())
        ;

        $this->entityManager->persist($activity);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new Activity());
    }

    public function edit(Request $request, Activity $activity): void
    {
        $this->persist($request, $activity);
    }
}
