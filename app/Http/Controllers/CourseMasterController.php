<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CourseMasterStoreReq;
use App\Http\Requests\CourseMasterUpdateReq;

use App\Http\Repositories\Finder\CourseMasterFinder;
use App\Http\Repositories\CourseMaster;

use App\Models\CourseMaster as Model;

class CourseMasterController extends Controller
{
    public function index(Request $request)
    {
        $finder = new CourseMasterFinder();
        $finder->setAccessControl($this->getAccessControl());

        $data = $finder->get();

        return view('pages.CourseMasterIndex', compact('data'));
    }

    public function store(CourseMasterStoreReq $request)
    {
        $row = new Model();
        $row->ref_no = $request->ref_no;
        $row->name = $request->name;

        $repo = new CourseMaster($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->save();

        return redirect()->back()->with('message', 'Master mata kuliah berhasil simpan.');
    }

    public function update(CourseMasterUpdateReq $request, $id)
    {
        $row = $this->getModel($id);
        $row->ref_no = $request->ref_no;
        $row->name = $request->name;

        $repo = new CourseMaster($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->save();

        return redirect()->back()->with('message', 'Master mata kuliah berhasil disimpan.');
    }

    public function destroy($id)
    {
        $row = $this->getModel($id);
        $repo = new CourseMaster($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->delete();

        return redirect()->back()->with('message', 'Master mata kuliah berhasil dihapus.');
    }

    public function show($id)
    {
        $data = $this->getModel($id);

        return view('pages.CourseMasterDetail', compact('data'));
    }

    private function getModel($id)
    {
        $row = Model::find($id);

        if (empty($row))
            abort(404, 'Kategori tidak ditemukan.');

        // insert $row to repository for checking access control
        $repo = new CourseMaster($row);
        $repo->setAccessControl($this->getAccessControl());

        $row = $repo->get();

        return $row;
    }
}
