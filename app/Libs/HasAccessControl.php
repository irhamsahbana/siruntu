<?php

namespace App\Libs;

use Illuminate\Support\Facades\Auth;

use App\Libs\AccessControl;

trait HasAccessControl
{
    protected $accessControl;

    public function setAccessControl($accessControl)
    {
        $this->accessControl = $accessControl;
    }

    public function getUserAccessControl()
    {
        return $this->accessControl;
    }

    public function filterByAccessControl($access, $message = 'null')
    {
        $accessControl = $this->getUserAccessControl();

        if ($accessControl)
            if(!$accessControl->hasAccess($access))
                AbstractAccessControl::throwUnauthorizedException($message);
    }
}