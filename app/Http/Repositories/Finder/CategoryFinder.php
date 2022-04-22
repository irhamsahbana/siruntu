<?php

namespace App\Http\Repositories\Finder;

use App\Models\Category;

class CategoryFinder extends AbstractFinder
{
    protected $groups = [];

    public function __construct()
    {
        $this->query = Category::select('id', 'name', 'group_by', 'label', 'notes');
    }

    public function setGroup($groups)
    {
        $this->groups = explode(",", $groups);
    }

    public function setKeyword($keyword)
    {
        if(!empty($keyword)) {
            $list = explode(' ', $keyword);
            $list = array_map('trim', $list);

            $this->query->where(function($query) use ($list) {
                foreach($list as $x) {
                    $pattern = '%' . $x . '%';
                    $query->orWhere('category.label', 'like', $pattern);
                }
            });
        }
    }

    private function whereOrderBy()
    {
        switch ($this->orderBy) {
            case 'name':
                $this->query->orderBy('name', $this->orderType);
                break;
            case 'label':
                $this->query->orderBy('label', $this->orderType);
                break;
        }
    }

    private function whereGroups()
    {
        if (count($this->groups) > 0)
            $this->query->whereIn('group_by', $this->groups);
    }

    protected function doQuery()
    {
        foreach ($this->groups as $group) {
            if ($group == 'permission_groups')
                $this->filterByAccessControl('access-right-read');
            else
                $this->filterByAccessControl(sprintf('category-%s-read', $group), sprintf('Tidak memiliki hak akses untuk melihat data %s', $group));
        }

        $this->whereOrderBy();
        $this->whereGroups();
    }
}