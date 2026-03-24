<?php 

namespace App\Service;

use App\Entity\Command;
use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CommandService
{
    private $mail;
    private $repository;
    private $entityManager;

    public function __construct(
        MailService $mailService,
        CommandRepository $commandRepository,
        EntityManagerInterface $entityManager)
    {
        $this->mail = $mailService;
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

        $message = nl2br("<html><body>Bonjour " . $data['gender'] . " " . $data['lastname'] . ",\n\nNous avons bien reçu votre message, nous reviendrons vers vous très rapidement\n\nCordialement</body></html>");
        
        $this->mail->sendMail($data['email'], 'Réception de votre message', $message);
        $this->mail->sendMail('toiwismart@gmail.com', 'Message reçu', $data['message']);
    }
}
