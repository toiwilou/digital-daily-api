<?php

namespace App\Service;

class AppBootService
{
    private $userService;
    private $activityService;
    private $bookingStatusService;
    private $transactionStatusService;
    private $verificationStatusService;

    public function __construct(
        UserService $userService,
        ActivityService $activityService,
        BookingStatusService $bookingStatusService,
        TransactionStatusService $transactionStatusService,
        VerificationStatusService $verificationStatusService)
    {
        $this->userService = $userService;
        $this->activityService = $activityService;
        $this->bookingStatusService = $bookingStatusService;
        $this->transactionStatusService = $transactionStatusService;
        $this->verificationStatusService = $verificationStatusService;
    }

    public function boot(): void
    {
        $this->activityService->createAll();
        $this->userService->createFirstUser();
        $this->bookingStatusService->createAll();
        $this->transactionStatusService->createAll();
        $this->verificationStatusService->createAll();
    }
}
