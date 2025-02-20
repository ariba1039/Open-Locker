<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Services\LockerServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lockerService = $this->createMock(LockerServiceInterface::class);
        $this->app->instance(LockerServiceInterface::class, $this->lockerService);
    }

    /**
     * Test the index method returns a successful response.
     */
    public function test_index_returns_successful_response(): void
    {
        Item::factory()->count(3);

        $response = $this->actingAs(User::factory()->create())->getJson('/api/items');

        $response->assertStatus(200);
    }

    /**
     * Test the index method returns the correct structure.
     */
    public function test_index_returns_correct_structure(): void
    {
        Item::factory()->count(3)->create();

        $response = $this->actingAs(User::factory()->create())->getJson('/api/items');

        $response->assertJsonStructure([

            [
                'id',
                'name',
                'description',
                'image_path',
                'locker_id',
            ],

        ]);
    }

    public function test_user_can_borrow_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['borrower_id' => null]);

        $this->lockerService->expects($this->once())
            ->method('openLocker')
            ->with($item->locker_id)
            ->willReturn(true);

        $response = $this->actingAs($user)->postJson(route('items.borrow', $item->id));

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => __('Item borrowed successfully'),
            ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'borrower_id' => $user->id,
        ]);
    }

    public function test_user_can_return_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['borrower_id' => $user->id]);

        $this->lockerService->expects($this->once())
            ->method('openLocker')
            ->with($item->locker_id)
            ->willReturn(true);

        $response = $this->actingAs($user)->postJson(route('items.return', $item->id));

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => __('Item returned successfully'),
            ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'borrower_id' => null,
        ]);
    }

    public function test_user_cannot_borrow_item_already_borrowed()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['borrower_id' => $user->id]);

        $response = $this->actingAs($user)->postJson(route('items.borrow', $item->id));

        $response->assertStatus(200)
            ->assertJson([
                'status' => false,
                'message' => __('Item is already borrowed'),
            ]);
    }

    public function test_user_cannot_return_item_not_borrowed()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['borrower_id' => null]);

        $response = $this->actingAs($user)->postJson(route('items.return', $item->id));

        $response->assertStatus(200)
            ->assertJson([
                'status' => false,
                'message' => __('Item is not borrowed'),
            ]);
    }

    public function test_get_borrowed_items_from_user_returns_correct_items()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $borrowedItems = Item::factory()->count(3)->create(['borrower_id' => $user->id]);
        $borrowedByOtherItems = Item::factory()->count(3)->create(['borrower_id' => $otherUser->id]);
        $otherItems = Item::factory()->count(2)->create(['borrower_id' => null]);

        $response = $this->actingAs($user)->getJson(route('items.borrowed'));

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'description', 'image_path', 'locker_id', 'borrowed'],
            ]);

        foreach ($borrowedItems as $item) {
            $response->assertJsonFragment([
                'id' => $item->id,
                'borrowed' => true,
            ]);
        }
    }
}
