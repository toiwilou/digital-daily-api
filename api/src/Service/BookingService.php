<?php

namespace App\Service;

use App\Entity\Booking;
use App\Traits\AppTrait;
use App\Repository\UserRepository;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AnnouncementRepository;
use Symfony\Component\HttpFoundation\Request;

class BookingService
{
    use AppTrait;

    private $repository;
    private $entityManager;
    private $userRepository;
    private $bookingStatusService;
    private $announcementRepository;

    public function __construct(
        UserRepository $userRepository,
        BookingRepository $bookingRepository,
        EntityManagerInterface $entityManager,
        BookingStatusService $bookingStatusService,
        AnnouncementRepository $announcementRepository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $bookingRepository;
        $this->userRepository = $userRepository;
        $this->bookingStatusService = $bookingStatusService;
        $this->announcementRepository = $announcementRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persist(Request $request, Booking $booking): void
    {
        $data = json_decode($request->getContent(), true);
        $status = $this->bookingStatusService->getById((int) $data['status']);
        $user = $this->userRepository->findOneBy(['id' => (int) $data['user']]);
        $announcement = $this->announcementRepository->findOneBy(['id' => (int) $data['announcement']]);

        $booking
            ->setUser($user ?? $booking->getUser())
            ->setAnnouncement($announcement ?? $booking->getAnnouncement())
            ->setDate($this->getAppCurrentDate())
            ->setStatus($status ?? $booking->getStatus())
            ->setActive(!!$data['active'] ?? $booking->isActive())
        ;

        $this->entityManager->persist($booking);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new Booking());
    }

    public function edit(Request $request, Booking $booking): void
    {
        $this->persist($request, $booking);
    }
}
