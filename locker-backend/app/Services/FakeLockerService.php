<?php

namespace App\Services;

use App\Entities\Locker;

class FakeLockerService implements LockerServiceInterface
{
    /** @var array<string, bool> */
    protected $fakeStatus = [];

    /**
     * @return array<Locker>
     */
    public function getLockerList(): array
    {
        return [
            new Locker('A-01', 1, 11, 111),
            new Locker('A-02', 2, 22, 222),
            new Locker('A-03', 3, 33, 333),
            new Locker('A-04', 4, 44, 444),
            new Locker('A-05', 5, 55, 555),
            new Locker('A-06', 6, 66, 666),
            new Locker('A-07', 7, 77, 777),
            new Locker('A-08', 8, 88, 888),
            new Locker('A-09', 9, 99, 999),
        ];
    }

    public function openLocker(string $lockerId): bool
    {
        $this->fakeStatus[$lockerId] = true;

        return true;
    }

    public function getLockerStatus(string $lockerId): bool
    {
        return $this->fakeStatus[$lockerId] ?? false;
    }
}
