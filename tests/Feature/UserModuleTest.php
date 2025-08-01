<?php

namespace Tests\Feature;

use App\Modules\User\Interfaces\UserServiceInterface;
use App\Modules\User\Models\User;
use App\Modules\User\Requests\StoreUserRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use function Pest\Stressless\stress;

class UserModuleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function it_can_create_a_user_via_api()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'phone' => '+1234567890',
            'address' => '123 Main St',
            'date_of_birth' => '1990-01-01',
            'is_active' => true,
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'item' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'date_of_birth',
                    'is_active',
                    'full_name',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'address' => '123 Main St',
            'is_active' => true,
        ]);
    }

    #[Test]
    public function it_can_retrieve_all_users()
    {
        // Create test users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'items' => [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'is_active',
                            'full_name',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                    'links',
                    'meta',
                ],
            ]);
    }

    #[Test]
    public function it_can_retrieve_specific_user()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'item' => [
                    'id' => $user->id,
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'is_active' => true,
                ],
            ]);
    }

    #[Test]
    public function it_can_update_user()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $updateData = [
            'name' => 'John Updated',
            'phone' => '+9876543210',
            'address' => '456 New St',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'item' => [
                    'id' => $user->id,
                    'name' => 'John Updated',
                    'phone' => '+9876543210',
                    'address' => '456 New St',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'John Updated',
            'phone' => '+9876543210',
            'address' => '456 New St',
        ]);
    }

    #[Test]
    public function it_can_delete_user()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    #[Test]
    public function it_can_activate_user()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'is_active' => false,
        ]);

        $response = $this->patchJson("/api/users/{$user->id}/activate");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'item' => [
                    'id' => $user->id,
                    'is_active' => true,
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => true,
        ]);
    }

    #[Test]
    public function it_can_deactivate_user()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $response = $this->patchJson("/api/users/{$user->id}/deactivate");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'item' => [
                    'id' => $user->id,
                    'is_active' => false,
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_user()
    {
        $response = $this->postJson('/api/users', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    #[Test]
    public function it_validates_email_uniqueness()
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/users', [
            'name' => 'Jane Doe',
            'email' => 'john@example.com', // Same email
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function user_service_can_retrieve_users()
    {
        $userService = app(UserServiceInterface::class);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $users = $userService->index(request());

        $this->assertCount(1, $users);
        $this->assertEquals('Test User', $users->first()->name);
    }

    #[Test]
    public function user_service_can_create_user()
    {
        $userService = app(UserServiceInterface::class);

        $request = StoreUserRequest::create('/fake', 'POST', [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password123',
            'is_active' => true,
        ]);

        // Make Laravel validate it like in a real request
        $request->setContainer(app())->validateResolved();

        $user = $userService->store($request);

        $this->assertEquals('New User', $user->name);
        $this->assertEquals('new@example.com', $user->email);
    }

    #[Test]
public function it_has_a_fast_response_time_for_users_endpoint()
{
    $endpoint = "http://localhost:8000/api/users";

    $concurrencyLevels = [5, 10, 20];
    $thresholdMs = 300;

    fwrite(STDOUT, "\n[INFO] Starting stress test for: {$endpoint}\n");

    foreach ($concurrencyLevels as $concurrency) {
        fwrite(STDOUT, "\n[INFO] Testing with {$concurrency} concurrent requests...\n");

        $result = stress($endpoint)->concurrency($concurrency)->duration(10)->run();

$totalRequests = $result->requests()->count();

        $durations = $result->requests()->duration();
        $min = $durations->min();
        $max = $durations->max();
        $median = $durations->med();
        $mean = $durations->avg();

    fwrite(STDOUT, "\n[INFO] Total Requests: {$totalRequests}\n");
        fwrite(STDOUT, sprintf(
            "[RESULT] Concurrency: %d | Min: %d ms | Max: %d ms | Median: %d ms | Mean: %d ms\n",
            $concurrency, $min, $max, $median, $mean
        ));

        expect($median)
            ->toBeLessThan($thresholdMs)
            ->and($max)
            ->toBeLessThan($thresholdMs * 3); // Allow some spikes
    }
}
}
