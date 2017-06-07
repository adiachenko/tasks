<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteUserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_can_only_delete_himself()
    {
        $user = factory(User::class)->create();
        $userB = factory(User::class)->create();

        $response = $this->json('delete', sprintf('api/users/%d', $userB->id), [], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(403);
    }

    public function test_delete_user()
    {
        $user = factory(User::class)->create([
            'first_name' => 'Jack'
        ]);

        $response = $this->json('delete', sprintf('api/users/%d', $user->id), [], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', [
            'first_name' => 'Jack'
        ]);
    }
}
