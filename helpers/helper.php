<?php

// generate json response
function json_response($message = null, $code = 200, $data = array())
{
    // clear the old headers
    header_remove();
    // set the actual code
    http_response_code($code);
    // set the header to make sure cache is forced
    header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    // treat this as json
    header('Content-Type: application/json');
    $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
    );
    // ok, validation error, or failure
    header('Status: '.$status[$code]);
    // return the encoded json
    return json_encode(array(
        'status' => $code < 300, // success or not?
        'message' => $message,
        'data' => $data
    ));
}

//render template
function render_template($vars,$path='landing/mail_templates/main_template.php')
{
    ob_start();
    extract($vars);
    require('App/Views/'.$path);
    return ob_get_clean();
}

// remove elements from array
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
// return value from array by key
function lang($key='', $arr = array())
{
    if($arr && is_array($arr) && isset($arr[$key])){
        echo $arr[$key];
    } else {
        echo $key;
    }
}
// generate url
function url($path){
    if(substr($path, 0, 1) === '/'){
        $path = ltrim($path, '/');
    }
    echo MAIN_URL.$path;
}

//nice var_dump and die
function dd($arr) {
    echo "<pre>";
    var_dump($arr);
    die;
}

// nice var_dump
function dump($arr) {
    echo "<pre>";
    var_dump($arr);
}

// generate random string
function str_random($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
// get value from trans folder
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

// get value from config folder
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

// get last element of array
function arr_last($arr){
    return array_values(array_slice($arr, -1))[0];
}

// get first element of array
function arr_start($arr){
    return array_shift(array_slice($arr, 0, 1));
}

// redirect to path
function redirect($path = '/'){
    header('Location: '.$path);
    exit();
};