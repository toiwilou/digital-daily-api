<?php

namespace App\Service;

use App\Entity\TransactionStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TransactionStatusRepository;

class TransactionStatusService
{
    private $repository;
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        TransactionStatusRepository $transactionStatusRepository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $transactionStatusRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function createAll(): void
    {
        $datas = json_decode(file_get_contents(__DIR__ . '/../../json/transactions_status.json'), true);
        
        foreach ($datas as $data) {
            $status = $this->repository->findOneBy(['name' => $data['name']]);

            if (!$status)
            {
                $status = new TransactionStatus();
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

    public function persist(Request $request, TransactionStatus $status): void
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
        $this->persist($request, new TransactionStatus());
    }

    public function edit(Request $request, TransactionStatus $status): void
    {
        $this->persist($request, $status);
    }
}
