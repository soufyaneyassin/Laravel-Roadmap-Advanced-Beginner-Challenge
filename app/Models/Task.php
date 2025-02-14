<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'user_id',
        'taskable_type',
        'taskable_id',
        'status',
        'priority'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taskable()
    {
        return $this->morphTo();
    }
}
