<?php

namespace App\Libs;

use App\Libs\AccessControl;

trait HasAccessControl
{
    protected $accessControl;

    public function setAccessControl(?AccessControl $accessControl)
    {
        $this->accessControl = $accessControl;
    }

    public function getUserAccessControl()
    {
        return $this->accessControl;
    }

    public function filterByAccessControl($access, $message = null)
    {
        $accessControl = $this->getUserAccessControl();

        if ($accessControl)
            if(!$accessControl->hasAccess($access))
                AbstractAccessControl::throwUnauthorizedException($message);
    }
}