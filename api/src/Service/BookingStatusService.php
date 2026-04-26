<?php

namespace App\Service;

use App\Entity\BookingStatus;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BookingStatusRepository;
use Symfony\Component\HttpFoundation\Request;

class BookingStatusService
{
    private $repository;
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        BookingStatusRepository $bookingStatusRepository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $bookingStatusRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): BookingStatus
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function getByName(string $name): BookingStatus
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    public function createAll(): void
    {
        $datas = json_decode(file_get_contents(__DIR__ . '/../../json/bookings_status.json'), true);
        
        foreach ($datas as $data) {
            $status = $this->repository->findOneBy(['name' => $data['name']]);

            if (!$status)
            {
                $status = new BookingStatus();
                $status
                    ->setName($data['name'])
                    ->setColor($data['color'])
                    ->setActive(true)
                ;

                $this->entityManager->persist($status);
                $this->entityManager->flush();
            }
        }
    }

    public function persist(Request $request, BookingStatus $status): void
    {
        $data = json_decode($request->getContent(), true);

        $status
            ->setName($data['name'] ?? $status->getName())
            ->setColor($data['color'] ?? $status->getColor())
            ->setActive(!!$data['active'] ?? $status->isActive())
        ;

        $this->entityManager->persist($status);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new BookingStatus());
    }

    public function edit(Request $request, BookingStatus $status): void
    {
        $this->persist($request, $status);
    }
}
