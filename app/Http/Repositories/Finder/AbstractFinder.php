<?php

namespace App\Http\Repositories\Finder;

use App\Libs\HasAccessControl;

abstract class AbstractFinder
{
    use HasAccessControl;

    protected $query;
    protected $isUsePagination = true;
    protected $orderBy;
    protected $orderType = 'asc';

    private $page = 1;
    private $perPage = 10;

    public function setPage($page)
    {
        $this->page = $page;
    }

    protected function getPage()
    {
        return $this->page;
    }

    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

    protected function getPerPage()
    {
        return $this->perPage;
    }

    public function setOrderBy(string $orderBy)
    {
        $this->orderBy = $orderBy;
    }

    public function setOrderType($orderType)
    {
        $orderType = strtolower($orderType);

        if ($orderType == 'asc' || $orderType == 'desc')
            $this->orderType = $orderType;
    }

    public function setIsUsePagination($isUsePagination)
    {
        $this->isUsePagination = $isUsePagination;
    }

    public function get()
    {
        $this->doQuery();

        if ($this->isUsePagination)
            $query = $this->query->paginate($this->getPerPage())->withQueryString();
        else
            $query = $this->query->get();

        return $query;
    }

    abstract protected function doQuery();
}