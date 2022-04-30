<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Repositories\Finder\CategoryFinder;
use App\Http\Repositories\Finder\CourseFinder;
use App\Http\Repositories\Finder\CourseMasterFinder;
use App\Http\Repositories\Finder\PersonFinder;

use App\Http\Repositories\Category;
use App\Http\Repositories\Course;
use App\Http\Repositories\CourseMaster;
use App\Http\Repositories\Learner;

use App\Models\Course as CourseModel;
use App\Models\CourseMaster as CourseMasterModel;
use App\Models\Category as CategoryModel;
use App\Models\Person as PersonModel;

class Select2Controller extends Controller
{
    public function categories(Request $request)
    {
        $finder = new CategoryFinder();
        $finder->setAccessControl($this->getAccessControl());

        if (!empty($request->category))
            $finder->setGroup($request->category);

        if ($request->keyword)
            $finder->setKeyword($request->keyword);

        $data = $finder->get();

        return response()->json($data);
    }

    public function category($id)
    {
        $data = $this->getModel($id, CategoryModel::class, Category::class);

        return response()->json($data);
    }

    public function courseMasters(Request $request)
    {
        $finder = new CourseMasterFinder();
        $finder->setAccessControl($this->getAccessControl());

        if ($request->keyword)
            $finder->setKeyword($request->keyword);

        $data = $finder->get();

        return response()->json($data);
    }

    public function courseMaster($id)
    {
        $data = $this->getModel($id, CourseMasterModel::class, CourseMaster::class);

        return response()->json($data);
    }

    public function courses(Request $request)
    {
        $finder = new CourseFinder();
        $finder->setAccessControl($this->getAccessControl());

        if ($request->keyword)
            $finder->setKeyword($request->keyword);

        $data = $finder->get();

        return response()->json($data);
    }

    public function course($id)
    {
        $data = $this->getModel($id, CourseModel::class, Course::class);

        return response()->json($data);
    }

    public function learners(Request $request)
    {
        $finder = new PersonFinder();
        $finder->setAccessControl($this->getAccessControl());
        $finder->setCategory('learner');

        if ($request->keyword)
            $finder->setKeyword($request->keyword);

        $data = $finder->get();

        return response()->json($data);
    }

    public function lecturers(Request $request)
    {
        $finder = new PersonFinder();
        $finder->setAccessControl($this->getAccessControl());
        $finder->setCategory('lecturer');

        if ($request->keyword)
            $finder->setKeyword($request->keyword);

        $data = $finder->get();

        return response()->json($data);
    }

    private function getModel(int $id, string $model, string $repository)
    {
        $row = $model::find($id);

        if (empty($row))
            abort(404, 'Data tidak ditemukan.');

        // insert $row to repository for checking access control
        $repo = new $repository($row);
        $repo->setAccessControl($this->getAccessControl());

        $row = $repo->get();

        return $row;
    }
}
