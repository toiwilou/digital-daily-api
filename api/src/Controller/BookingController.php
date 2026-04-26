<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Helper\AppHelper;
use App\Service\BookingService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/bookings')]
#[IsGranted('ROLE_USER')]
final class BookingController extends AbstractController
{
    private $key;
    private $helper;
    private $service;

    public function __construct(
        AppHelper $appHelper,
        BookingService $bookingService)
    {
        $this->key = 'booking';
        $this->helper = $appHelper;
        $this->service = $bookingService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $bookings = $this->helper->serialize($collection, $this->key);

        return new JsonResponse($bookings, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Booking|null $booking): JsonResponse
    {
        if (!$booking) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $booking);

        return new JsonResponse(200);
    }
}
