<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTesst extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    private $list;
    private $todoList;
    public function setUp():void {
        parent::setUp();
        $this->authUser();
        $this->todoList = $this->createTodoList();
        $this->list = $this->createTask(['todo_list_id' => $this->todoList->id]);
    }

    public function test_fetch_all_tasks_of_a_todo_list()
    {
        
        $todoList =  $this->createTodoList();
        $todoList1 =  $this->createTodoList();
        $label = $this->createLabel(['user_id' => auth()->id()]);
        $tasks = $this->createTask(['todo_list_id' => $todoList->id, 'label_id' => $label->id]);
        $respon = $this->getJson(route('todo-list.task.index', $todoList->id))->assertOk()->json('data');
        $this->assertEquals(1, count($respon));
        $this->assertEquals($respon[0]['title'], $tasks->title);
        // $this->assertEquals($respon[0]['todo_list_id'], $todoList->id);
    }

    // public function test_fetch_a_task_of_a_todo_list()
    // {
    //     $respon = $this->getJson(route('task.show', $this->list->id))->assertOk()->json();

    //     $this->assertEquals($respon['title'], $this->list->title);
    // }

    public function test_while_field_title_store_a_task_of_a_todo_list()
    {
        $this->withExceptionHandling();
        $this->postJson(route('todo-list.task.store', $this->todoList->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
    }

    public function test_store_task_of_a_todo_list () {
        //preperation / prepera
        $list = Task::factory()->make();
        $todoList = $this->todoList;
        $label = $this->createLabel();

        // action
        $this->postJson(route('todo-list.task.store', $todoList->id), [
            'title' => $list->title,
            'label_id' => $label->id,
        ])->assertCreated();

        // assertion
        $this->assertDatabaseHas('tasks', ['title' => $list->title, 'todo_list_id' => $todoList->id, 'label_id' => $label->id]);
    }

    public function test_store_task_of_a_todo_list_without_a_label () {
        //preperation / prepera
        $list = Task::factory()->make();
        $todoList = $this->todoList;

        // action
        $task = $this->postJson(route('todo-list.task.store', $todoList->id), [
            'title' => $list->title,
        ])->assertCreated();

        // assertion
        $this->assertDatabaseHas('tasks', ['title' => $list->title, 'todo_list_id' => $todoList->id, 'label_id' => null]);
    }

    public function test_delete_task_of_a_todo_list () {
        //preperation / prepera
        
        // action
        $this->deleteJson(route('task.destroy', $this->list->id))
        ->assertNoContent();
        // assertion
        $this->assertDatabaseMissing('tasks', ['title' => $this->list->title]);
    }

    public function test_while_field_title_update_a_task_of_a_todo_list()
    {
        $this->withExceptionHandling();
        
        $this->patchJson(route('task.update', $this->list->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
    }

    public function test_update_task_of_a_todo_list () {
        //preperation / prepera
        
        // action
        $this->patchJson(route('task.update', $this->list->id), ['title' => 'my task updated'])->assertOk();
        // assertion
        $this->assertDatabaseHas('tasks', ['id' => $this->list->id, 'title' => 'my task updated']);
    }

    public function test_change_status_task () {
        //preperation / prepera

        // action
        $this->patchJson(route('task.update', $this->list->id), ['status' => Task::STARTED])->assertOk();
        // assertion
        $this->assertDatabaseHas('tasks', ['status' => Task::STARTED]);
    }
}
