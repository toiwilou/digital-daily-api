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

    public function add(Request $request): void
    {
        $command = new Command();
        $data = json_decode($request->getContent(), true);
        
        $command
            ->setIsCompany(!!$data['is_company'] ?? $command->isCompany())
            ->setCompany($data['company'] ?? $command->getCompany())
            ->setGender($data['gender'] ?? $command->getGender())
            ->setFirstname($data['firstname'] ?? $command->getFirstname())
            ->setLastname($data['lastname'] ?? $command->getLastname())
            ->setEmail($data['email'] ?? $command->getEmail())
            ->setSubject($data['subject'] ?? $command->getSubject())
            ->setMessage($data['message'] ?? $command->getMessage())
            ->setActive(true)
        ;

        $this->entityManager->persist($command);
        $this->entityManager->flush();
    }
}
