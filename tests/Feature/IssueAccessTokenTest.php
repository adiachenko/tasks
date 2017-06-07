<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IssueAccessTokenTest extends TestCase
{
    use DatabaseMigrations;

    public function test_issue_token_denied_for_uers_with_unconfirmed_email()
    {
        factory(User::class)->create([
            'email' => 'jack@mail.com',
            'password' => bcrypt('secret')
        ]);

        $response = $this->json('post', 'api/auth/token', [
            'email' => 'jack@mail.com',
            'password' => 'secret'
        ]);

        $response->assertStatus(401);
    }

    public function test_issue_token()
    {
        $user = factory(User::class)->create([
            'email' => 'jack@mail.com',
            'email_confirmed' => true,
            'password' => bcrypt('secret')
        ]);

        $response = $this->json('post', 'api/auth/token', [
            'email' => 'jack@mail.com',
            'password' => 'secret'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['api_token']
            ])
            ->assertJsonFragment(['api_token' => $user->api_token]);
    }

    public function test_email_is_required()
    {
        $response = $this->json('post', 'api/auth/token', [
            'password' => 'secret'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['email']
            ]);
    }

    public function test_password_is_required()
    {
        $response = $this->json('post', 'api/auth/token', [
            'email' => 'jack@mail.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['password']
            ]);
    }
}
