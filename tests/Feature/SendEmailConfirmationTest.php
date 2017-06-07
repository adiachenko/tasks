<?php

namespace Tests\Feature;

use App\Notifications\ConfirmEmail;
use App\User;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SendEmailConfirmationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_not_found_when_user_has_already_confirmed_email()
    {
        $user = factory(User::class)->create([
            'email' => 'jack@mail.com',
            'email_confirmed' => true,
        ]);

        $response = $this->json('post', 'api/email-confirmations', [
            'email' => $user->email
        ]);

        $response->assertStatus(404);

        $this->assertDatabaseMissing('email_confirmations', [
            'email' => $user->email
        ]);
    }

    public function test_confirm_email()
    {
        Notification::fake();

        $user = factory(User::class)->create([
            'email' => 'jack@mail.com'
        ]);

        $response = $this->json('post', 'api/email-confirmations', [
            'email' => $user->email
        ]);

        $response->assertStatus(202)
            ->assertJsonStructure([
                'meta' => ['message']
            ]);

        $this->assertDatabaseHas('email_confirmations', [
            'email' => $user->email
        ]);

        Notification::assertSentTo($user, ConfirmEmail::class);
    }

    public function test_email_is_required()
    {
        factory(User::class)->create();

        $response = $this->json('post', 'api/email-confirmations');

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['email']
            ]);
    }
}
