<?php

namespace App\Libs;

use Illuminate\Database\Eloquent\Model;

class AbstractAccessControl
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function hasAccessOrThrow($access, $message = null)
    {
        if(!$this->hasAccess($access)) {
            self::throwUnauthorizedException($message);
        }
    }

    /*
     * Throw exception template
     */
    public static function throwUnauthorizedException($message = null)
    {
        if(empty($message))
            $message = 'Anda tidak punya akses untuk aksi ini.';

        abort(403, $message);
    }
}