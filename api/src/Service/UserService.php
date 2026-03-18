<?php 

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserService
{
    private $params;
    private $repository;
    private $entityManager;
    private $passwordHasher;

    public function __construct(
        ParameterBagInterface $params,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher)
    {
        $this->params = $params;
        $this->repository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function createFirstUser(): void
    {
        $email = $this->params->get('email');
        $user = $this->repository->findOneBy(['email' => $email]);

        if (!$user)
        {
            $user = new User();
            $user
                ->setFirstname($this->params->get('firstname'))
                ->setLastname($this->params->get('lastname'))
                ->setEmail($email)
                ->setPassword($this->passwordHasher->hashPassword(
                        $user, $this->params->get('password')
                ))
                ->setActive(true)
            ;

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persist(Request $request, User $user): void
    {
        $data = json_decode($request->getContent(), true);
        
        $user
            ->setFirstname($data['firstname'] ?? $user->getFirstname())
            ->setLastname($data['lastname'] ?? $user->getLastname())
            ->setEmail($data['email'] ?? $user->getEmail())
            ->setPassword($this->passwordHasher->hashPassword(
                $user, $data['password']
            ) ?? $user->getPassword())
            ->setActive(!!$data['active'] ?? $user->isActive())
            ->setPicture($data['picture'] ?? $user->getPicture())
            ->setResetToken($data['resset_token'] ?? $user->getResetToken())
        ;

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new User());
    }

    public function edit(Request $request, User $user): void
    {
        $this->persist($request, $user);
    }
}
