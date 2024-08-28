<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function documentation(): string
    {
        return view('documentation');
    }
}
