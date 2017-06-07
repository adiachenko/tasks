<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewTaskTest extends TestCase
{
    use DatabaseMigrations;

    public function test_list_tasks()
    {
        factory(Task::class)->create();

        $response = $this->json('get', sprintf('api/tasks'));

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

    public function test_show_task()
    {
        $task = factory(Task::class)->create();

        $response = $this->json('get', sprintf('api/tasks/%d', $task->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'description', 'completed', 'created_at', 'updated_at']
            ]);
    }
}
