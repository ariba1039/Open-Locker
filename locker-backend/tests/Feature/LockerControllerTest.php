<?php

namespace Tests\Feature;

use App\Models\Locker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LockerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $lockerService;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_admin_can_get_locker_list(): void
    {
        // Create an admin user
        $admin = User::factory()->create();
        $admin->makeAdmin();

        $lockers = Locker::factory()->count(3)->create();

        // Execute the request
        $response = $this->actingAs($admin)->getJson(route('admin.lockers.index'));

        // Check the response
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'is_open',
                ],
            ]);

        $response->assertJsonCount(3);
    }

    public function test_admin_can_open_locker(): void
    {
        // Create an admin user
        $admin = User::factory()->create();
        $admin->makeAdmin();

        $locker = Locker::factory()->create();

        // Execute the request
        $response = $this->actingAs($admin)->postJson(route('admin.lockers.open', ['locker' => $locker->first()->id]));

        // Check the response
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => __('Not yet implemented'),
            ]);
    }

    public function test_non_admin_cannot_access_locker_endpoints(): void
    {
        // Create a regular user
        $user = User::factory()->create();

        $locker = Locker::factory()->create();

        // Try to get the locker list
        $response = $this->actingAs($user)->getJson(route('admin.lockers.index'));
        $response->assertStatus(403);

        // Try to open a locker
        $response = $this->actingAs($user)->postJson(route('admin.lockers.open', ['locker' => $locker->first()->id]));
        $response->assertStatus(403);
    }

    public function test_admin_cannot_open_nonexistent_locker(): void
    {
        // Create an admin user
        $admin = User::factory()->create();
        $admin->makeAdmin();

        // Execute the request
        $response = $this->actingAs($admin)->postJson(route('admin.lockers.open', ['locker' => 'NICHT-VORHANDEN']));

        // Check the response
        $response->assertStatus(404);
    }

    public function test_unauthenticated_user_cannot_access_locker_endpoints(): void
    {
        // Try to get the locker list without authentication
        $response = $this->getJson(route('admin.lockers.index'));
        $response->assertStatus(401); // Unauthenticated

        $locker = Locker::factory()->create();

        // Try to open a locker without authentication
        $response = $this->postJson(route('admin.lockers.open', ['locker' => $locker->first()->id]));
        $response->assertStatus(401); // Unauthenticated
    }
}
