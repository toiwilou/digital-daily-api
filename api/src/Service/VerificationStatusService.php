<?php

namespace App\Service;

use App\Entity\BookingStatus;
use App\Entity\VerivicationStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\VerivicationStatusRepository;

class VerificationStatusService
{
    private $repository;
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        VerivicationStatusRepository $verivicationStatusRepository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $verivicationStatusRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function createAll(): void
    {
        $datas = json_decode(file_get_contents(__DIR__ . '/../../json/verifications_status.json'), true);
        
        foreach ($datas as $data) {
            $status = $this->repository->findOneBy(['name' => $data['name']]);

            if (!$status)
            {
                $status = new VerivicationStatus();
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

    public function persist(Request $request, VerivicationStatus $status): void
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
        $this->persist($request, new VerivicationStatus());
    }

    public function edit(Request $request, VerivicationStatus $status): void
    {
        $this->persist($request, $status);
    }
}
