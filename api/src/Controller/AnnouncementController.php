<?php

namespace App\Controller;

use App\Helper\AppHelper;
use App\Entity\Announcement;
use App\Service\AnnouncementService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/api/announcements')]
final class AnnouncementController extends AbstractController
{
    private $key;
    private $helper;
    private $service;

    public function __construct(
        AppHelper $appHelper,
        AnnouncementService $announcementService)
    {
        $this->helper = $appHelper;
        $this->key = 'announcement';
        $this->service = $announcementService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $announcements = $this->helper->serialize($collection, $this->key);

        return new JsonResponse($announcements, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Announcement|null $announcement): JsonResponse
    {
        if (!$announcement) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $announcement);

        return new JsonResponse(200);
    }
}

