<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;
    public const STARTED = 'started';
    public const PENDING = 'pending';
    public const NOT_STARTED = 'not_started';

    protected $fillable = ['title', 'description', 'todo_list_id', 'label_id', 'status'];

    public function todo_list () : BelongsTo{
        return $this->belongsTo(TodoList::class);
    }

    public function label () : BelongsTo{
        return $this->belongsTo(Label::class);
    }
}
