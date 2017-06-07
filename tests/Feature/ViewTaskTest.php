<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewTaskTest extends TestCase
{
    use DatabaseMigrations;

    public function test_list_tasks_denied_for_unauthorized_users()
    {
        factory(Task::class)->create();

        $response = $this->json('get', sprintf('api/tasks'));

        $response->assertStatus(401);
    }

    public function test_list_tasks()
    {
        $user = factory(User::class)->create();
        factory(Task::class)->create();

        $response = $this->json('get', sprintf('api/tasks'), [], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    ['id', 'name', 'description', 'completed', 'created_at', 'updated_at']
                ],
                'meta' => [
                    'pagination' => ['current_page', 'per_page', 'total']
                ],
            ]);
    }

    public function test_show_task_denied_for_unauthorized_users()
    {
        $task = factory(Task::class)->create();

        $response = $this->json('get', sprintf('api/tasks/%d', $task->id));

        $response->assertStatus(401);
    }

    public function test_show_task()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create();

        $response = $this->json('get', sprintf('api/tasks/%d', $task->id), [], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'description', 'completed', 'created_at', 'updated_at']
            ]);
    }
}
