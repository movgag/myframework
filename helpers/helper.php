<?php

function remove_from_arr($array, $should_remove=array()){
    if(is_array($should_remove) && $should_remove){
        foreach ($should_remove as $item){
            if(array_key_exists($item,$array)){
                unset($array[$item]);
            }
        }
    }
    return $array;
}

function lang($key='', $arr = array())
{
    if($arr && is_array($arr) && isset($arr[$key])){
        echo $arr[$key];
    } else {
        echo $key;
    }
}

function url($path){
    if(substr($path, 0, 1) === '/'){
        $path = ltrim($path, '/');
    }
    echo MAIN_URL.$path;
}

function dd($arr) {
    echo "<pre>";
    var_dump($arr);
    die;
}

function dump($arr) {
    echo "<pre>";
    var_dump($arr);
}

function str_random($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function trans($path){
    $path_parts = explode('.',$path);
    if(is_array($path_parts) && count($path_parts) == 3){

        $arr = include('trans/'.$path_parts[0].'/'.$path_parts[1].'.php');

        if(is_array($arr) && $arr && isset($arr[$path_parts[2]])){

            return $arr[$path_parts[2]];
        } else {
            return '';
        }
    } else {
        return '';
    }
}

function config($path){

    $path_parts = explode('.',$path);
    if(is_array($path_parts) && count($path_parts) == 2){

        $arr = include('config/'.$path_parts[0].'.php');

        if(is_array($arr) && $arr && isset($arr[$path_parts[1]])){

            return $arr[$path_parts[1]];
        } else {
            return '';
        }
    } else {
        return '';
    }
}

function arr_last($arr){
    return array_values(array_slice($arr, -1))[0];
}

function arr_start($arr){
    return array_shift(array_slice($arr, 0, 1));
}

function redirect($path = '/'){
    header('Location: '.$path);
    exit();
};