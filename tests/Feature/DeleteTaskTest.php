<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteTaskTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_delete_task()
    {
        $task = factory(Task::class)->create([
            'name' => 'Task name'
        ]);

        $response = $this->json('delete', sprintf('api/tasks/%d', $task->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', [
            'name' => 'Task name'
        ]);
    }
}
