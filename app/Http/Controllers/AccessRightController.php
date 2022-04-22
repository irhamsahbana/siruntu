<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Repositories\Finder\CategoryFinder;
use App\Http\Repositories\AccessRight;

use App\Http\Requests\AccessRightStoreReq;
use App\Http\Requests\AccessRightUpdateReq;

use App\Models\Category as Model;
use App\Models\Meta;

class AccessRightController extends Controller
{
    public function index(Request $request)
    {
        $finder = new CategoryFinder();
        $finder->setAccessControl($this->getAccessControl());
        $finder->setGroup('permission_groups');

        $data = $finder->get();

        return view('pages.AccessRightIndex', compact('data'));
    }

    public function store(AccessRightStoreReq $request)
    {
        $row = new Model();
        $row->label = $request->label;
        $row->notes = $request->notes;

        $repo = new AccessRight($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->save();

        return redirect()->back()->with('message', 'Hak akses berhasil disimpan.');
    }

    public function update(AccessRightUpdateReq $request, $id)
    {
        $row = $this->getModel($id);
        $row->label = $request->label;
        $row->notes = $request->notes;

        $repo = new AccessRight($row);
        $repo->setAccessControl($this->getAccessControl());

        if ($request->permission_ids)
        foreach ($request->permission_ids as $permission_id)
            $repo->addPermission($permission_id);

        $repo->save();

        return redirect()->back()->with('message', 'Hak akses berhasil disimpan.');
    }

    public function show($id)
    {
        $data = $this->getModel($id);
        $permissions = Model::where('group_by', 'permissions')->get();
        $groupPermissions = Meta::select('value')->where([
            'key' => 'permission_id',
            'table_name' => $data->getTable(),
            'fk_id' => $data->id
        ])->get()->pluck('value');

        return view('pages.AccessRightDetail', compact('data', 'permissions', 'groupPermissions'));
    }

    public function destroy($id)
    {
        $row = $this->getModel($id);

        $repo = new AccessRight($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->delete();

        return redirect()->back()->with('message', 'Hak akses berhasil dihapus.');
    }

    private function getModel($id)
    {
        $row = Model::where('id', $id)->where('group_by', 'permission_groups')->first();

        if (empty($row))
            abort(404, 'Hak Akses tidak ditemukan.');

        // insert $row to repository for checking access control
        $repo = new AccessRight($row);
        $repo->setAccessControl($this->getAccessControl());

        $row = $repo->get();

        return $row;
    }
}
