<?php

namespace App\Services;

use App\Entities\Locker;

interface LockerServiceInterface
{
    /**
     * Get the list of locker_ids
     *
     * @return array<Locker>
     */
    public function getLockerList(): array;

    public function openLocker(string $lockerId): bool;

    public function getLockerStatus(string $lockerId): bool;
}
