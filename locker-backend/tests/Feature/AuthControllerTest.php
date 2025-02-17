<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_register()
    {
        $adminUser = User::factory()->create();
        $token = $adminUser->createToken('auth_token')->plainTextToken;

        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'token',
                'name',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'name' => $userData['name'],
        ]);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        $existingUser = User::factory()->create();
        $token = $existingUser->createToken('auth_token')->plainTextToken;

        $userData = [
            'name' => $this->faker->name,
            'email' => $existingUser->email, // Verwende bereits existierende E-Mail
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_verification_email_is_sent_on_registration()
    {
        Notification::fake();

        $adminUser = User::factory()->create();

        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($adminUser)->postJson('/api/register', $userData);

        $response->assertStatus(201);

        Notification::assertSentTo(
            [User::where('email', $userData['email'])->first()],
            VerifyEmail::class
        );
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'name',
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(422);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logged out successfully',
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_user_can_get_their_info()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,

            ]);
    }

    public function test_unauthenticated_user_cannot_access_protected_routes()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    public function test_registration_validation_rules()
    {
        $adminUser = User::factory()->create();
        $token = $adminUser->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123', // zu kurz
            'password_confirmation' => '456', // stimmt nicht überein
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_login_validation_rules()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'not-an-email',
            'password' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_user_can_verify_email()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->getJson($verificationUrl);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Email verified',
            ]);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_user_cannot_verify_email_with_invalid_signature()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $invalidVerificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->subMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->getJson($invalidVerificationUrl);

        $response->assertStatus(403);
    }

    public function test_send_verification_email()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->postJson(Route('verification.send'));

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Email verification link sent',
            ]);

        Notification::assertSentTo(
            [$user], VerifyEmail::class
        );
    }

    public function test_send_verification_email_already_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/email/verification-notification');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Email already verified',
            ]);
    }
}
