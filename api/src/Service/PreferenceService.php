<?php

namespace App\Service;

use App\Traits\AppTrait;
use App\Entity\Preference;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PreferenceRepository;
use App\Repository\AnnouncementRepository;
use Symfony\Component\HttpFoundation\Request;

class PreferenceService
{
    use AppTrait;

    private $repository;
    private $entityManager;
    private $activityRepository;
    private $announcementRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ActivityRepository $activityRepository,
        PreferenceRepository $preferenceRepository,
        AnnouncementRepository $announcementRepository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $preferenceRepository;
        $this->activityRepository = $activityRepository;
        $this->announcementRepository = $announcementRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persist(Request $request, Preference $preference): void
    {
        $data = json_decode($request->getContent(), true);
        $activities = $data['activities'];

        foreach ($activities as $activity) {
            $preference->addActivity($this->activityRepository->findOneBy(['id' => $activity]));
        }

        $preference->setActive(!!$data['active'] ?? $preference->isActive());

        $this->entityManager->persist($preference);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new Preference());
    }

    public function edit(Request $request, Preference $preference): void
    {
        $this->persist($request, $preference);
    }
}
