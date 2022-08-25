<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\ClassroomStoreReq;
use App\Http\Requests\ClassroomUpdateReq;

use App\Http\Repositories\Finder\ClassroomFinder;
use App\Http\Repositories\Classroom;

use App\Services\Classroom as Service;

use App\Models\Classroom as Model;
use App\Models\User;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        $finder = new ClassroomFinder();
        $finder->setAccessControl($this->getAccessControl());

        if ($request->course_id)
            $finder->setCourse($request->course_id);

        $data = $finder->get();

        return view('pages.ClassroomIndex', compact('data'));
    }

    public function myClassroom()
    {
        $groups = Auth::user()->getUserPermissionGroups()->pluck('name');

        if ($groups->contains('lecturer') || $groups->contains('learner'))
            $data = Classroom::myClassrom(Auth::user()->person_id);
        else
            return redirect()->back()->withErrors('Anda tidak teridentifikasi sebagai dosen atau mahasiswa.');

        return view('pages.ClassroomFollowedList', compact('data'));
    }

    public function classroomLiveCourse($classroom_id)
    {
        $groups = Auth::user()->getUserPermissionGroups()->pluck('name');
        $registered = DB::table('classroom_participants')
                        ->where('classroom_id', $classroom_id)
                        ->where('person_id', Auth::user()->person_id)
                        ->exists();

        if (($groups->contains('lecturer') || $groups->contains('learner')) && $registered) {
            $profile = User::where('id', Auth::id())->with(['person.category'])->first();

            return view('pages.LiveCourseIndex', compact('profile'));
        } else {
            return redirect()->back()->withErrors('Anda tidak terdaftar pada kelas.');
        }
    }

    public function store(ClassroomStoreReq $request)
    {
        $row = new Model();
        $row->course_id = $request->course_id;
        $row->mode_id = $request->mode_id;
        $row->name = $request->name;

        $repo = new Classroom($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->save();

        return redirect()->back()->with('message', 'Ruang kelas berhasil simpan.');
    }

    public function update(ClassroomUpdateReq $request, $id)
    {
        $row = $this->getModel($id);
        $row->course_id = $request->course_id;
        $row->mode_id = $request->mode_id;
        $row->name = $request->name;

        $repo = new Classroom($row);
        $repo->setAccessControl($this->getAccessControl());

        if ($request->add_participants)
            $repo->addParticipants($request->add_participants);

        $repo->save();

        return redirect()->back()->with('message', 'Ruang kelas berhasil disimpan.');
    }

    public function destroy($id)
    {
        $row = $this->getModel($id);
        $repo = new Classroom($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->delete();

        return redirect()->back()->with('message', 'Ruang kelas berhasil dihapus.');
    }

    public function show($id, Service $service)
    {
        $data = $this->getModel($id);
        $participants = $service->getClassroomParticipants($id);

        return view('pages.ClassroomDetail', compact('data', 'participants'));
    }

    public function removeParticipants(Request $request, $id)
    {
        $row = $this->getModel($id);

        $repo = new Classroom($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->removeParticipants($request->delete_participants);

        return redirect()->back()->with('message', 'Peserta Kelas berhasil dihapus.');
    }

    private function getModel($id)
    {
        $row = Model::find($id);

        if (empty($row))
            abort(404, 'Ruang kelas tidak ditemukan.');

        // insert $row to repository for checking access control
        $repo = new Classroom($row);
        $repo->setAccessControl($this->getAccessControl());

        $row = $repo->get();

        return $row;
    }
}
