<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Helper\AppHelper;
use App\Service\ActivityService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/activities')]
final class ActivityController extends AbstractController
{
    private $key;
    private $helper;
    private $service;

    public function __construct(
        AppHelper $appHelper,
        ActivityService $activityService)
    {
        $this->key = 'activity';
        $this->helper = $appHelper;
        $this->service = $activityService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getCategories();
        $activities = $this->helper->serialize($collection, $this->key);

        return new JsonResponse($activities, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Activity|null $activity): JsonResponse
    {
        if (!$activity) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $activity);

        return new JsonResponse(200);
    }
}
