<?php

namespace Core;

class View {

    public static function show($viewData, $viewPath , $is_admin = false, $other_layout = '' ) {
        extract($viewData) ;
        $folder = 'admin/';
        if($is_admin){
            $layout = $folder.'adminlayout.php';
            if($other_layout){
                $layout = $other_layout;
            }
        } else {
            $folder = 'landing/';
            $layout = $folder.'mainlayout.php';
            if($other_layout){
                $layout = $other_layout;
            }
        }
        require('App/Views/'.$layout);
    }


}