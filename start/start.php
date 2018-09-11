<?php

session_start();

// loading main config file
is_readable(dirname(__DIR__).'/config/config.php') ? require_once 'config/config.php' : exit('Config file is missing');

// loadin default helper file
is_readable(dirname(__DIR__).'/helpers/helper.php') ? require_once 'helpers/helper.php' : exit('Helper file is missing');

// loading composer packages
is_readable(dirname(__DIR__).'/vendor/autoload.php') ? require_once 'vendor/autoload.php' : exit('Composer autoload file is missing');

function directories_autoload($class) {
    $root = dirname(__DIR__);

    $file = $root . '/' . str_replace('\\', '/', ucfirst($class ) ) . '.php';

    is_readable($file) ? require $file : '';
}

try {
    spl_autoload_register('directories_autoload');
} catch (\Exception $e){
    dd($e->getMessage());
}
