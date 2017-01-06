<?php

use Controllers\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
        $this->render('home/home', array(

        ));
    }
}