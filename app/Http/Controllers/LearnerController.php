<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\PersonStoreReq;
use App\Http\Requests\PersonUpdateReq;

use App\Http\Repositories\Finder\PersonFinder;
use App\Http\Repositories\Learner;

use App\Models\Person as Model;

class LearnerController extends Controller
{
    public function index(Request $request)
    {
        $finder = new PersonFinder();
        $finder->setAccessControl($this->getAccessControl());
        $finder->setCategory('learner');

        $data = $finder->get();

        return view('pages.LearnerIndex', compact('data'));
    }

    public function store(PersonStoreReq $request)
    {
        $row = new Model();
        $row->ref_no = $request->ref_no;
        $row->name = $request->name;
        $row->email = $request->email;

        $repo = new Learner($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->save();

        return redirect()->back()->with('message', 'Mahasiswa berhasil simpan.');
    }

    public function update(PersonUpdateReq $request, $id)
    {
        $row = $this->getModel($id);
        $row->ref_no = $request->ref_no;
        $row->name = $request->name;
        $row->email = $request->email;

        $repo = new Learner($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->save();

        return redirect()->back()->with('message', 'Mahasiswa berhasil disimpan.');
    }

    public function destroy($id)
    {
        $row = $this->getModel($id);
        $repo = new Learner($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->delete();

        return redirect()->back()->with('message', 'Mahasiswa berhasil dihapus.');
    }

    public function show($id)
    {
        $data = $this->getModel($id);

        return view('pages.LearnerDetail', compact('data'));
    }

    private function getModel($id)
    {
        $row = Model::find($id);

        if (empty($row))
            abort(404, 'Mahasiswa tidak ditemukan.');

        // insert $row to repository for checking access control
        $repo = new Learner($row);
        $repo->setAccessControl($this->getAccessControl());

        $row = $repo->get();

        return $row;
    }
}
