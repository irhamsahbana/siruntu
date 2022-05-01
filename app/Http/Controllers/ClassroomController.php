<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\ClassroomStoreReq;
use App\Http\Requests\ClassroomUpdateReq;

use App\Http\Repositories\Finder\ClassroomFinder;
use App\Http\Repositories\Classroom;

use App\Models\Classroom as Model;

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

    public function show($id)
    {
        $data = $this->getModel($id);

        return view('pages.ClassroomDetail', compact('data'));
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
