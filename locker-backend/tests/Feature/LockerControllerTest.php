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

        // Mock des LockerService
        $this->lockerService = $this->createMock(LockerServiceInterface::class);
        $this->app->instance(LockerServiceInterface::class, $this->lockerService);
    }

    public function test_admin_can_get_locker_list(): void
    {
        // Erstelle einen Admin-Benutzer
        $admin = User::factory()->create();
        $admin->makeAdmin();

        // Mock der Locker-Liste
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

        // Führe die Anfrage aus
        $response = $this->actingAs($admin)->getJson(route('admin.lockers.index'));

        // Überprüfe die Antwort
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'is_open'
                ]
            ]);
    }

    public function test_admin_can_open_locker(): void
    {
        // Erstelle einen Admin-Benutzer
        $admin = User::factory()->create();
        $admin->makeAdmin();

        // Mock der openLocker-Methode
        $this->lockerService->expects($this->once())
            ->method('openLocker')
            ->with('A-01')
            ->willReturn(true);

        // Führe die Anfrage aus
        $response = $this->actingAs($admin)->postJson(route('admin.lockers.open', ['lockerId' => 'A-01']));

        // Überprüfe die Antwort
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => __('Locker successfully opened.'),
            ]);
    }

    public function test_non_admin_cannot_access_locker_endpoints(): void
    {
        // Erstelle einen normalen Benutzer
        $user = User::factory()->create();

        // Versuche, die Locker-Liste abzurufen
        $response = $this->actingAs($user)->getJson(route('admin.lockers.index'));
        $response->assertStatus(403);

        // Versuche, einen Locker zu öffnen
        $response = $this->actingAs($user)->postJson(route('admin.lockers.open', ['lockerId' => 'A-01']));
        $response->assertStatus(403);
    }
    
    public function test_admin_cannot_open_nonexistent_locker(): void
    {
        // Erstelle einen Admin-Benutzer
        $admin = User::factory()->create();
        $admin->makeAdmin();

        // Mock der openLocker-Methode, die fehlschlägt
        $this->lockerService->expects($this->once())
            ->method('openLocker')
            ->with('NICHT-VORHANDEN')
            ->willReturn(false);

        // Führe die Anfrage aus
        $response = $this->actingAs($admin)->postJson(route('admin.lockers.open', ['lockerId' => 'NICHT-VORHANDEN']));

        // Überprüfe die Antwort
        $response->assertStatus(500)
            ->assertJson([
                'status' => false,
                'message' => __('Failed to open locker.'),
            ]);
    }
    
    public function test_admin_can_get_locker_with_different_status(): void
    {
        // Erstelle einen Admin-Benutzer
        $admin = User::factory()->create();
        $admin->makeAdmin();

        // Mock der Locker-Liste
        $lockers = [
            new Locker('A-01', 1, 11, 111),
            new Locker('A-02', 2, 22, 222),
        ];

        $this->lockerService->expects($this->once())
            ->method('getLockerList')
            ->willReturn($lockers);

        // Konfiguriere unterschiedliche Status für die Locker
        $this->lockerService->expects($this->exactly(2))
            ->method('getLockerStatus')
            ->willReturnMap([
                ['A-01', true],  // Erster Locker ist geöffnet
                ['A-02', false], // Zweiter Locker ist geschlossen
            ]);

        // Führe die Anfrage aus
        $response = $this->actingAs($admin)->getJson(route('admin.lockers.index'));

        // Überprüfe die Antwort
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
        // Versuche, die Locker-Liste abzurufen ohne Authentifizierung
        $response = $this->getJson(route('admin.lockers.index'));
        $response->assertStatus(401); // Unauthenticated

        // Versuche, einen Locker zu öffnen ohne Authentifizierung
        $response = $this->postJson(route('admin.lockers.open', ['lockerId' => 'A-01']));
        $response->assertStatus(401); // Unauthenticated
    }
} 