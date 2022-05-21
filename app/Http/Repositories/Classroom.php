<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\DB;

use App\Models\Classroom as Model;
use App\Models\Category;

class Classroom extends AbstractRepository
{
    private $participants = [];

    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function save()
    {
        $this->filterByAccessControl('classroom-create');
        parent::save();
    }

    public function delete($permanent = null)
    {
        $this->filterByAccessControl('classroom-delete');
        parent::delete($permanent);
    }

    public function get()
    {
        $this->filterByAccessControl('classroom-read');
        return $this->model;
    }

    protected function beforeSave()
    {
        if (empty($this->model->mode_id))
            $this->model->mode_id = Category::where('group_by', 'classroom_modes')
                                            ->where('name', 'normal'
                                            )->first()->id;
    }

    protected function afterSave()
    {
        $this->syncWithAdditionalParticipants();
    }

    protected function beforeDelete()
    {
        // detach all participants from classroom
        $this->model->participants()->detach();
    }

    public function addParticipants(array $participants)
    {
        $this->participants = $participants;
    }

    private function syncWithAdditionalParticipants()
    {
        if (!empty($this->participants)) {
            $participants = collect($this->participants);

            $participants = $participants->mapWithKeys(function ($participant) {
                return [
                    $participant => ['course_id' => $this->model->course_id],
                ];
            })->toArray();

            $this->model->participants()->syncWithoutDetaching($participants);
        }
    }

    public function removeParticipants(?array $participants)
    {
        $this->filterByAccessControl('classroom-delete');
        $this->model->participants()->detach($participants);
    }

    public static function myClassrom($personId)
    {
        return DB::table('classroom_participants')
                    ->join('classrooms', 'classrooms.id', 'classroom_participants.classroom_id')
                    ->join('courses', 'courses.id', 'classrooms.course_id')
                    ->where('person_id', $personId)
                    ->select('classrooms.*', 'courses.ref_no as course_ref_no', 'courses.name as course_name')
                    ->paginate(9);
    }
}