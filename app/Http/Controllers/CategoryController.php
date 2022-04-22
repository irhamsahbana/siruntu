<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Repositories\Finder\CategoryFinder;
use App\Http\Repositories\Category;

use App\Http\Requests\CategoryStoreReq;
use App\Http\Requests\CategoryUpdateReq;

use App\Models\Category as Model;

class CategoryController extends Controller
{
    public function list()
    {
        return view('pages.CategoryList');
    }

    public function index(Request $request)
    {
        $finder = new CategoryFinder();
        $finder->setAccessControl($this->getAccessControl());

        if (!empty($request->category))
            $finder->setGroup($request->category);
        else
            return redirect()->back()->withErrors('Kategori tidak ditemukan.');

        $data = $finder->get();

        return view('pages.CategoryIndex', compact('data'));
    }

    public function store(CategoryStoreReq $request)
    {
        $row = new Model();
        $row->label = $request->label;
        $row->notes = $request->notes;
        $row->group_by = $request->group_by;

        $repo = new Category($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->save();

        return redirect()->back()->with('message', 'Kategori berhasil disimpan.');
    }

    public function update(CategoryUpdateReq $request, $id)
    {
        $row = $this->getModel($id);
        $row->label = $request->label;
        $row->notes = $request->notes;
        $row->group_by = $request->group_by;

        $repo = new Category($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->save();

        return redirect()->back()->with('message', 'Kategori berhasil disimpan.');
    }

    public function show($id)
    {
        $data = $this->getModel($id);

        return view('pages.CategoryDetail', compact('data'));
    }

    public function destroy($id)
    {
        $row = $this->getModel($id);

        $repo = new Category($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->delete();

        return redirect()->back()->with('message', 'Kategori berhasil dihapus.');
    }

    private function getModel($id)
    {
        $row = Model::find($id);

        if (empty($row))
            abort(404, 'Kategori tidak ditemukan.');

        // insert $row to repository for checking access control
        $repo = new Category($row);
        $repo->setAccessControl($this->getAccessControl());

        $row = $repo->get();

        return $row;
    }
}
