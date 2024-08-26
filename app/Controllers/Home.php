<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('dashboard');
    }
    public function documentation(): string
    {
        return view('documentation');
    }
}
