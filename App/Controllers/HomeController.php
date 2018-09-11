<?php

namespace App\Controllers;

use Core\View;
use App\Models\TestModel;

class HomeController extends BaseController {

    public function index()
    {
        $data = array();
        $data['title'] = 'Home';

        View::show($data,'App/Views/landing/index.php');
    }


}