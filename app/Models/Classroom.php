<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function mode()
    {
        return $this->belongsTo(Category::class);
    }

    public function participants()
    {
        return $this->belongsToMany(
            Person::class,
            'classroom_participants',
            'classroom_id',
            'person_id'
        );
    }
}
