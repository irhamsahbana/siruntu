<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Repositories\Finder\CategoryFinder;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $finder = new CategoryFinder();
        $finder->setAccessControl($this->getAccessControl());

        $data = $finder->get();

        return view('');
    }
}
