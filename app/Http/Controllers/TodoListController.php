<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListRequest;
use App\Http\Resources\TodoListResource;
use App\Models\TodoList;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    public function index () {
        $list = auth()->user()->todo_lists;
        return TodoListResource::collection($list);
    }

    public function show (TodoList $todo_list) {
        return new TodoListResource($todo_list);
    }

    public function store (TodoListRequest $request) {
        $todo_list = auth()->user()->todo_lists()->create($request->validated());
        return new TodoListResource($todo_list);
    }

    public function destroy (TodoList $todo_list) {
        $todo_list->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    public function update (TodoListRequest $request, TodoList $todo_list) {
        $todo_list->update($request->all());
        return new TodoListResource($todo_list);
    }
}
