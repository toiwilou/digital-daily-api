<?php

namespace App\Controller;

use App\Entity\User;
use App\Helper\AppHelper;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/users')]
#[IsGranted('ROLE_USER')]
final class UserController extends AbstractController
{
    private $key;
    private $helper;
    private $service;

    public function __construct(
        AppHelper $appHelper,
        UserService $userService)
    {
        $this->key = 'user';
        $this->helper = $appHelper;
        $this->service = $userService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $users = $this->helper->serialize($collection,  $this->key);

        return new JsonResponse($users, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, User|null $user): JsonResponse
    {
        if (!$user) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $user);
        
        return new JsonResponse(200);
    }
}
