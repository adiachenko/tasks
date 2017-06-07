<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ToggleTaskCompletionTest extends TestCase
{
    use DatabaseMigrations;

    public function test_toggle_task_completion()
    {
        $task = factory(Task::class)->create([
            'completed' => false
        ]);

        $response = $this->json('patch', sprintf('api/tasks/%d/completed', $task->id), [
            'completed' => true,
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseHas('tasks', [
            'completed' => true
        ]);
    }

    public function test_completed_is_required()
    {
        $task = factory(Task::class)->create([
            'completed' => false
        ]);

        $response = $this->json('patch', sprintf('api/tasks/%d/completed', $task->id));

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['completed']
            ]);
    }
}
