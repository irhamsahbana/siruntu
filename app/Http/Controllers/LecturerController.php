<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\PersonStoreReq;
use App\Http\Requests\PersonUpdateReq;

use App\Http\Repositories\Finder\PersonFinder;
use App\Http\Repositories\Lecturer;

use App\Models\Person as Model;

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        $finder = new PersonFinder();
        $finder->setAccessControl($this->getAccessControl());
        $finder->setCategory('lecturer');

        $data = $finder->get();

        return view('pages.LecturerIndex', compact('data'));
    }

    public function store(PersonStoreReq $request)
    {
        $row = new Model();
        $row->ref_no = $request->ref_no;
        $row->name = $request->name;
        $row->email = $request->email;

        $repo = new Lecturer($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->save();

        return redirect()->back()->with('message', 'Dosen berhasil simpan.');
    }

    public function update(PersonUpdateReq $request, $id)
    {
        $row = $this->getModel($id);
        $row->ref_no = $request->ref_no;
        $row->name = $request->name;
        $row->email = $request->email;

        $repo = new Lecturer($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->save();

        return redirect()->back()->with('message', 'Dosen berhasil disimpan.');
    }

    public function destroy($id)
    {
        $row = $this->getModel($id);
        $repo = new Lecturer($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->delete();

        return redirect()->back()->with('message', 'Dosen berhasil dihapus.');
    }

    public function show($id)
    {
        $data = $this->getModel($id);

        return view('pages.LecturerDetail', compact('data'));
    }

    private function getModel($id)
    {
        $row = Model::find($id);

        if (empty($row))
            abort(404, 'Dosen tidak ditemukan.');

        // insert $row to repository for checking access control
        $repo = new Lecturer($row);
        $repo->setAccessControl($this->getAccessControl());

        $row = $repo->get();

        return $row;
    }
}
