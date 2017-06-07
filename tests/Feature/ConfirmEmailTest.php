<?php

namespace Tests\Feature;

use App\EmailConfirmation;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConfirmEmailTest extends TestCase
{
    use DatabaseMigrations;

    public function test_confirm_email()
    {
        $user = factory(User::class)->create(['email_confirmed' => false]);
        $confirmation = factory(EmailConfirmation::class)->create(['email' => $user->email]);

        $response = $this->json('patch', 'api/users/email', [
            'id' => $confirmation->id,
            'email' => $confirmation->email,
        ]);

        $response->assertStatus(204);

        $this
            ->assertDatabaseHas('users', [
                'email' => $user->email,
                'email_confirmed' => true
            ])
            ->assertDatabaseMissing('email_confirmations', [
                'email' => $user->email
            ]);
    }

    public function test_confirm_email_denied_if_confirmation_is_expired()
    {
        $duration = config('auth.email_confirmations.expire');

        $user = factory(User::class)->create(['email_confirmed' => false]);
        $confirmation = factory(EmailConfirmation::class)->create([
            'email' => $user->email,
            'updated_at' => Carbon::now()->subMinutes(++$duration)
        ]);

        $response = $this->json('patch', 'api/users/email', [
            'id' => $confirmation->id,
            'email' => $confirmation->email,
        ]);

        $response->assertStatus(403);
    }

    public function test_id_is_required()
    {
        $user = factory(User::class)->create(['email_confirmed' => false]);
        $confirmation = factory(EmailConfirmation::class)->create(['email' => $user->email]);

        $response = $this->json('patch', 'api/users/email', [
            'email' => $confirmation->email,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['id']
            ]);
    }

    public function test_email_is_required()
    {
        $user = factory(User::class)->create(['email_confirmed' => false]);
        $confirmation = factory(EmailConfirmation::class)->create(['email' => $user->email]);

        $response = $this->json('patch', 'api/users/email', [
            'id' => $confirmation->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['email']
            ]);
    }
}
