<?php

namespace App\Http\Controllers;


class HomeController extends Base
{
    public function index()
    {

        return $this->render('Home::index', '控制台');

    }


}
