<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ToggleTaskCompletionTest extends TestCase
{
    use DatabaseMigrations;

    public function test_toggle_task_completion_denied_for_unauthorized_users()
    {
        $task = factory(Task::class)->create();

        $response = $this->json('patch', sprintf('api/tasks/%d/completed', $task->id), [
            'completed' => true,
        ]);

        $response->assertStatus(401);
    }

    public function test_toggle_task_completion()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'completed' => false
        ]);

        $response = $this->json('patch', sprintf('api/tasks/%d/completed', $task->id), [
            'completed' => true,
        ], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseHas('tasks', [
            'completed' => true
        ]);
    }

    public function test_completed_is_required()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'completed' => false
        ]);

        $response = $this->json('patch', sprintf('api/tasks/%d/completed', $task->id), [], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['completed']
            ]);
    }
}
