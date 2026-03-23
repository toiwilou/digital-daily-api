<?php

namespace App\Controller;

use App\Entity\Command;
use App\Helper\AppHelper;
use App\Service\CommandService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/commands')]
final class CommandController extends AbstractController
{
    private $key;
    private $helper;
    private $service;

    public function __construct(
        AppHelper $appHelper,
        CommandService $userService)
    {
        $this->key = 'command';
        $this->helper = $appHelper;
        $this->service = $userService;
    }

    #[IsGranted('ROLE_USER')]
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
    public function update(Request $request, Command|null $command): JsonResponse
    {
        if (!$command) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $command);
        
        return new JsonResponse(200);
    }
}
