<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'todo_list' => $this->todo_list->name,
            'label' => new LabelResource($this->label),
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
