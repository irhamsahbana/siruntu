<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Classroom
{
    public function getClassroomParticipants($classroomId)
    {
        return DB::table('classroom_participants')
                ->select('classroom_participants.*', 'people.ref_no', 'people.name', 'person_categories.name AS category_name')
                ->leftJoin('people', 'people.id', '=', 'classroom_participants.person_id')
                ->leftJoin('categories AS person_categories', 'person_categories.id', '=', 'people.category_id')
                ->where('classroom_participants.classroom_id', $classroomId)
                ->get();
    }

}