<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CourseStoreReq;
use App\Http\Requests\CourseUpdateReq;

use App\Http\Repositories\Finder\CourseFinder;
use App\Http\Repositories\Course;

use App\Models\Course as Model;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $finder = new CourseFinder();
        $finder->setAccessControl($this->getAccessControl());

        $data = $finder->get();

        return view('pages.CourseIndex', compact('data'));
    }

    public function store(CourseStoreReq $request)
    {
        $row = new Model();
        $row->course_master_id = $request->course_master_id;
        $row->semester_id = $request->semester_id;

        $repo = new Course($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->save();

        return redirect()->back()->with('message', 'Master mata kuliah berhasil simpan.');
    }

    public function update(CourseUpdateReq $request, $id)
    {
        $row = $this->getModel($id);
        $row->course_master_id = $request->course_master_id;
        $row->semester_id = $request->semester_id;

        $repo = new Course($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->save();

        return redirect()->back()->with('message', 'Master mata kuliah berhasil disimpan.');
    }

    public function destroy($id)
    {
        $row = $this->getModel($id);
        $repo = new Course($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->delete();

        return redirect()->back()->with('message', 'Master mata kuliah berhasil dihapus.');
    }

    public function show($id)
    {
        $data = $this->getModel($id);

        return view('pages.CourseDetail', compact('data'));
    }

    private function getModel($id)
    {
        $row = Model::find($id);

        if (empty($row))
            abort(404, 'Master mata kuliah tidak ditemukan.');

        // insert $row to repository for checking access control
        $repo = new Course($row);
        $repo->setAccessControl($this->getAccessControl());

        $row = $repo->get();

        return $row;
    }
}
