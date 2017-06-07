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

    public function test_list_users()
    {
        factory(User::class)->create();

        $response = $this->json('get', 'api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    ['email', 'first_name', 'last_name']
                ]
            ]);
    }

    public function test_list_users_without_exposing_private_data()
    {
        factory(User::class)->create();

        $response = $this->json('get', 'api/users');

        $response->assertStatus(200)
            ->assertJsonMissing([
                'password', 'api_token'
            ]);
    }

    public function test_show_user()
    {
        $user = factory(User::class)->create();

        $response = $this->json('get', sprintf('api/users/%d', $user->id));

        $response->assertStatus(200)
            ->assertJsonMissing([
                'password', 'api_token'
            ]);
    }
}
