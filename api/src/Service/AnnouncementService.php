<?php

namespace App\Service;

use App\Traits\AppTrait;
use App\Entity\Announcement;
use App\Repository\UserRepository;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AnnouncementRepository;
use Symfony\Component\HttpFoundation\Request;

class AnnouncementService
{
    use AppTrait;

    private $repository;
    private $entityManager;
    private $userRepository;
    private $activityRepository;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        ActivityRepository $activityRepository,
        AnnouncementRepository $announcementRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->repository = $announcementRepository;
        $this->activityRepository = $activityRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persist(Request $request, Announcement $announcement): void
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userRepository->findOneBy(['id' => (int) $data['user']]);
        $activity = $this->activityRepository->findOneBy(['id' => (int) $data['activity']]);

        $announcement
            ->setUser($user ?? $announcement->getUser())
            ->setActivity($activity ?? $announcement->getActivity())
            ->setDetail($data['detail'] ?? $announcement->getDetail())
            ->setCreatedAt($this->getAppCurrentDate())
            ->setActive(!!$data['active'] ?? $announcement->isActive())
        ;

        $this->entityManager->persist($announcement);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new Announcement());
    }

    public function edit(Request $request, Announcement $announcement): void
    {
        $this->persist($request, $announcement);
    }
}
