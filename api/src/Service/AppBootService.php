<?php

namespace App\Service;

class AppBootService
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function boot(): void
    {
        $this->userService->createFirstUser();
    }
}
