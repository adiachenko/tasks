<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SaveTaskTest extends TestCase
{
    use DatabaseMigrations;

    public function test_create_task_denied_for_unathorized_users()
    {
        $response = $this->json('post', 'api/tasks', [
            'name' => 'Task name',
            'description' => 'Task description',
        ]);

        $response->assertStatus(401);
    }

    public function test_create_task()
    {
        $user = factory(User::class)->create();

        $response = $this->json('post', 'api/tasks', [
            'name' => 'Task name',
            'description' => 'Task description',
        ], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'description', 'completed', 'created_at', 'updated_at']
            ]);
        $this->assertDatabaseHas('tasks', [
            'name' => 'Task name',
            'description' => 'Task description',
            'completed' => false,
        ]);
    }

    public function test_name_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->json('post', 'api/tasks', [], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['name']
            ]);
    }

    public function test_name_cant_exceed_255_characters()
    {
        $user = factory(User::class)->create();

        $response = $this->json('post', 'api/tasks', [
            'name' => 'gBcUdeYCX3bI0TtaHYzVV1FnHUratwfOYnkMq10PbzGUHm3AR1amUHOW8KRMnvh7dHYQOjYyOdLD9bSRFBv5xn2GlwH1ropdvAcKl00rR4JuEsZzrTknIPoV4Ab942cKF2dVPQCqraTK7aTtaUtWjBY5Xt52YfeerQ6bEeA1cOq3CM5MoOcEA8gaAeWdy172fusFprJTNTyZoUtrPn4lhVg5rQPIq2MUyc1UstT0bXrtanrwdNtsFfNVj480Sld1',
        ], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => ['name']
            ]);
    }

    public function test_update_task_denied_for_unauthorized_users()
    {
        $task = factory(Task::class)->create();

        $response = $this->json('patch', sprintf('api/tasks/%d', $task->id), [
            'name' => 'New task name',
            'description' => 'New task description',
        ]);

        $response->assertStatus(401);
    }

    public function test_update_task()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create();

        $response = $this->json('patch', sprintf('api/tasks/%d', $task->id), [
            'name' => 'New task name',
            'description' => 'New task description',
        ], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'description', 'completed', 'created_at', 'updated_at']
            ]);
        $this->assertDatabaseHas('tasks', [
            'name' => 'New task name',
            'description' => 'New task description',
        ]);
    }

    public function test_partially_update_task()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'description' => 'Original description'
        ]);

        $response = $this->json('patch', sprintf('api/tasks/%d', $task->id), [
            'name' => 'New task name'
        ], [
            'Authorization' => sprintf('Bearer %s', $user->api_token),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'description', 'completed', 'created_at', 'updated_at']
            ]);
        $this->assertDatabaseHas('tasks', [
            'name' => 'New task name',
            'description' => 'Original description',
        ]);
    }
}
