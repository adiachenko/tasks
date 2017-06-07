<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewUserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_list_users_denied_for_unauthorized_users()
    {
        $response = $this->json('get', 'api/users');

        $response->assertStatus(401);
    }

    public function test_list_users()
    {
        $user = factory(User::class)->times(2)->create()->first();

        $response = $this->json('get', 'api/users', [], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    ['email', 'first_name', 'last_name']
                ]
            ]);
        $this->assertEquals(2, count($response->json()['data']));
    }

    public function test_list_users_without_exposing_private_data()
    {
        $user = factory(User::class)->create();

        $response = $this->json('get', 'api/users', [], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(200)
            ->assertJsonMissing([
                'password', 'api_token'
            ]);
    }

    public function test_show_user_denied_for_unauthorized_users()
    {
        $user = factory(User::class)->create();

        $response = $this->json('get', sprintf('api/users/%d', $user->id));

        $response->assertStatus(401);
    }

    public function test_show_user()
    {
        $user = factory(User::class)->create();
        $userB = factory(User::class)->create();

        $response = $this->json('get', sprintf('api/users/%d', $userB->id), [], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['email', 'first_name', 'last_name']
            ]);
    }
}
