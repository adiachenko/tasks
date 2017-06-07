<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteTaskTest extends TestCase
{
    use DatabaseMigrations;

    public function test_delete_task_denied_for_unauthorized_users()
    {
        $task = factory(Task::class)->create([
            'name' => 'Task name'
        ]);

        $response = $this->json('delete', sprintf('api/tasks/%d', $task->id));

        $response->assertStatus(401);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_delete_task()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'name' => 'Task name'
        ]);

        $response = $this->json('delete', sprintf('api/tasks/%d', $task->id), [], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', [
            'name' => 'Task name'
        ]);
    }
}
