<?php

namespace App\Controller;

use App\Entity\Preference;
use App\Helper\AppHelper;
use App\Service\PreferenceService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[IsGranted('ROLE_USER')]
#[Route('/api/preferences')]
final class PreferenceController extends AbstractController
{
    private $key;
    private $helper;
    private $service;

    public function __construct(
        AppHelper $appHelper,
        PreferenceService $preferenceService)
    {
        $this->key = 'preference';
        $this->helper = $appHelper;
        $this->service = $preferenceService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $preferences = $this->helper->serialize($collection, $this->key);

        return new JsonResponse($preferences, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Preference|null $preference): JsonResponse
    {
        if (!$preference) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $preference);

        return new JsonResponse(200);
    }
}
