<?php

namespace App\Listeners;

use App\Events\UserSaved;
use App\Services\UserServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveUserBackgroundInformation
{
    protected $user_service;
    /**
     * Create the event listener.
     */
    public function __construct(UserServiceInterface $user_service)
    {
        $this->user_service = $user_service;
    }

    /**
     * Handle the event.
     */
    public function handle(UserSaved $event): void
    {
        $user = $event->user;
        $this->user_service->saveDetails($user);
    }
}
