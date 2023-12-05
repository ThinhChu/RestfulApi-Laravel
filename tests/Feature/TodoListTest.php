<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;
    private $list;

    public function setUp (): void {
        parent::setUp();
        $user = $this->authUser();
        $this->list = $this->createTodoList(['user_id' => $user->id]);
    }
    /**
     * A basic feature test example.
     */
    public function test_todo_list_index(): void
    {
        // preperation / prepera
        $this->createTodoList();
        // action / perform
        $response = $this->getJson(route('todo-list.index'));

        // assertion / predict
        $this->assertEquals(1, count($response->json('data')));
    }

    public function test_fetch_single_todo_list (): void
    {
        // preperation / prepera

        // action / perform
        $response = $this->getJson(route('todo-list.show', $this->list->id))->assertOk()->json('data');

        // assertion / predict
        $this->assertEquals($response['name'], $this->list->name);
    }

    public function test_store_new_todo_list (): void
    {
        // preperation / prepera
        $list = TodoList::factory()->make();
        // action / perform
        $response = $this->postJson(route('todo-list.store', ['name' => $list->name]))->assertCreated()->json('data');
        // assertion / predict
        $this->assertEquals($response['name'], $list->name);
        $this->assertDatabaseHas('todo_lists', ['name' => $list->name]);
    }

    public function test_while_storing_todo_list_name_field_is_requeried ()
    {
        // preperation / prepera
        $this->withExceptionHandling();

        // action / perform
        $this->postJson(route('todo-list.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);

        // assertion / predict

    }

    public function test_delete_todo_list () {
        // preperation / prepera
        

        // action / perform
        $this->deleteJson(route('todo-list.destroy', $this->list->id))
             ->assertNoContent();

        // assertion / predict
        $this->assertDatabaseMissing('todo_lists', ['name' => $this->list->name]);

    }

    public function test_update_todo_list () {
        // preperation / prepera
        

        // action / perform
        $this->patchJson(route('todo-list.update', $this->list->id), ['name' => 'name updated'])
            ->assertOk();

        // assertion / predict
        $this->assertDatabaseHas('todo_lists', ['id' => $this->list->id, 'name' => 'name updated']);

    }

    public function test_while_update_todo_list_name_field_is_requeried ()
    {
        // preperation / prepera
        $this->withExceptionHandling();

        // action / perform
        $this->patchJson(route('todo-list.update', $this->list->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);

        // assertion / predict

    }
}
