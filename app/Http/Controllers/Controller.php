<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $skipAuthenticate;

    public function __construct()
    {
        if ($this->skipAuthenticate) {
            $this->middleware('checkToken', ['except' => $this->skipAuthenticate]);
        } else {
            $this->middleware('checkToken');
        }
    }
}
