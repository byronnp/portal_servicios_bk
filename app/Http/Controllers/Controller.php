<?php

namespace App\Http\Controllers;

use App\Models\Responders\ApiResponder;

abstract class Controller
{
    protected ApiResponder $responder;

    public function __construct()
    {
        $this->responder = new ApiResponder();
    }
}
