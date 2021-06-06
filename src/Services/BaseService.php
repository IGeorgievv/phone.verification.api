<?php
declare(strict_types=1);

namespace App\Services;

class BaseService
{
    public static $models = [];
    public static $_session = [];

    protected $session = null;

    protected $request = null;

    protected $Authentication = null;

    function __construct($request = null, $Authentication = null, $session = null)
    {
        if (isset($session)) {
            self::$_session = $session;
            $this->session = $session;
        } else {
            $this->session = self::$_session;
        }
        $this->request = $request;
        $this->Authentication = $Authentication;
    }
}