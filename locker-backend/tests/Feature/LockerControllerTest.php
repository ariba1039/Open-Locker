<?php

namespace Tests\Feature;

use App\Entities\Locker;
use App\Models\User;
use App\Services\LockerServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LockerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $lockerService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the LockerService
        $this->lockerService = $this->createMock(LockerServiceInterface::class);
        $this->app->instance(LockerServiceInterface::class, $this->lockerService);
    }

    public function test_admin_can_get_locker_list(): void
    {
        // Create an admin user
        $admin = User::factory()->create();
        $admin->makeAdmin();

        // Mock the locker list
        $lockers = [
            new Locker('A-01', 1, 11, 111),
            new Locker('A-02', 2, 22, 222),
        ];

        $this->lockerService->expects($this->once())
            ->method('getLockerList')
            ->willReturn($lockers);

        $this->lockerService->expects($this->exactly(2))
            ->method('getLockerStatus')
            ->willReturn(false);

        // Execute the request
        $response = $this->actingAs($admin)->getJson(route('admin.lockers.index'));

        // Check the response
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'modbus_address',
                    'coil_register',
                    'status_register',
                    'is_open'
                ]
            ]);
    }

    public function test_admin_can_open_locker(): void
    {
        // Create an admin user
        $admin = User::factory()->create();
        $admin->makeAdmin();

        // Mock the openLocker method
        $this->lockerService->expects($this->once())
            ->method('openLocker')
            ->with('A-01')
            ->willReturn(true);

        // Execute the request
        $response = $this->actingAs($admin)->postJson(route('admin.lockers.open', ['lockerId' => 'A-01']));

        // Check the response
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => __('Locker successfully opened.'),
            ]);
    }

    public function test_non_admin_cannot_access_locker_endpoints(): void
    {
        // Create a regular user
        $user = User::factory()->create();

        // Try to get the locker list
        $response = $this->actingAs($user)->getJson(route('admin.lockers.index'));
        $response->assertStatus(403);

        // Try to open a locker
        $response = $this->actingAs($user)->postJson(route('admin.lockers.open', ['lockerId' => 'A-01']));
        $response->assertStatus(403);
    }
    
    public function test_admin_cannot_open_nonexistent_locker(): void
    {
        // Create an admin user
        $admin = User::factory()->create();
        $admin->makeAdmin();

        // Mock the openLocker method that fails
        $this->lockerService->expects($this->once())
            ->method('openLocker')
            ->with('NICHT-VORHANDEN')
            ->willReturn(false);

        // Execute the request
        $response = $this->actingAs($admin)->postJson(route('admin.lockers.open', ['lockerId' => 'NICHT-VORHANDEN']));

        // Check the response
        $response->assertStatus(500)
            ->assertJson([
                'status' => false,
                'message' => __('Failed to open locker.'),
            ]);
    }
    
    public function test_admin_can_get_locker_with_different_status(): void
    {
        // Create an admin user
        $admin = User::factory()->create();
        $admin->makeAdmin();

        // Mock the locker list
        $lockers = [
            new Locker('A-01', 1, 11, 111),
            new Locker('A-02', 2, 22, 222),
        ];

        $this->lockerService->expects($this->once())
            ->method('getLockerList')
            ->willReturn($lockers);

        // Configure different statuses for the lockers
        $this->lockerService->expects($this->exactly(2))
            ->method('getLockerStatus')
            ->willReturnMap([
                ['A-01', true],  // First locker is open
                ['A-02', false], // Second locker is closed
            ]);

        // Execute the request
        $response = $this->actingAs($admin)->getJson(route('admin.lockers.index'));

        // Check the response
        $response->assertStatus(200)
            ->assertJson([
                [
                    'id' => 'A-01',
                    'is_open' => true,
                ],
                [
                    'id' => 'A-02',
                    'is_open' => false,
                ],
            ]);
    }
    
    public function test_unauthenticated_user_cannot_access_locker_endpoints(): void
    {
        // Try to get the locker list without authentication
        $response = $this->getJson(route('admin.lockers.index'));
        $response->assertStatus(401); // Unauthenticated

        // Try to open a locker without authentication
        $response = $this->postJson(route('admin.lockers.open', ['lockerId' => 'A-01']));
        $response->assertStatus(401); // Unauthenticated
    }
}
