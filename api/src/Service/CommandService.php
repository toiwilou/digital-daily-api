<?php 

namespace App\Service;

use App\Entity\Command;
use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CommandService
{
    private $repository;
    private $entityManager;

    public function __construct(
        CommandRepository $commandRepository,
        EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $commandRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persist(Request $request, Command $command): void
    {
        $data = json_decode($request->getContent(), true);
        
        $command
            ->setIsCompany(!!$data['is_company'] ?? $command->isCompany())
            ->setCompany($data['company'] ?? $command->getCompany())
            ->setGender($data['gender'] ?? $command->getGender())
            ->setFirstname($data['firstname'] ?? $command->getFirstname())
            ->setLastname($data['lastname'] ?? $command->getLastname())
            ->setEmail($data['email'] ?? $command->getEmail())
            ->setEmail($data['email'] ?? $command->getEmail())
            ->setObject($data['object'] ?? $command->getObject())
            ->setMessage($data['message'] ?? $command->getMessage())
            ->setActive(!!$data['active'] ?? $command->isActive())
        ;

        $this->entityManager->persist($command);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new Command());
    }

    public function edit(Request $request, Command $command): void
    {
        $this->persist($request, $command);
    }
}
