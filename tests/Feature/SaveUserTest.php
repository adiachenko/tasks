<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SaveUserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_create_user()
    {
        $response = $this->json('post', 'api/users', [
            'email' => 'jack@mail.com',
            'first_name' => 'Jack',
            'last_name' => 'Bauer',
            'password' => 'secret',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'jack@mail.com',
            'first_name' => 'Jack',
            'last_name' => 'Bauer',
        ]);
    }

    public function test_email_is_required()
    {
        $response = $this->json('post', 'api/users', [
            'first_name' => 'Jack',
            'last_name' => 'Bauer',
            'password' => 'secret',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['email']
            ]);
    }

    public function test_email_is_valid_email()
    {
        $response = $this->json('post', 'api/users', [
            'email' => 'Nah',
            'first_name' => 'Jack',
            'last_name' => 'Bauer',
            'password' => 'secret',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['email']
            ]);
    }

    public function test_first_name_is_required()
    {
        $response = $this->json('post', 'api/users', [
            'email' => 'jack@mail.com',
            'last_name' => 'Bauer',
            'password' => 'secret',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['first_name']
            ]);
    }

    public function test_last_name_is_required()
    {
        $response = $this->json('post', 'api/users', [
            'email' => 'jack@mail.com',
            'first_name' => 'Jack',
            'password' => 'secret',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['last_name']
            ]);
    }

    public function test_password_is_required()
    {
        $response = $this->json('post', 'api/users', [
            'email' => 'jack@mail.com',
            'first_name' => 'Jack',
            'last_name' => 'Bauer',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['password']
            ]);
    }

    public function test_password_is_at_least_6_characters_long()
    {
        $response = $this->json('post', 'api/users', [
            'email' => 'jack@mail.com',
            'first_name' => 'Jack',
            'last_name' => 'Bauer',
            'password' => 'secre',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['password']
            ]);
    }
}
