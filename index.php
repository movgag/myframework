<?php
//initialisation of project
require 'start/start.php';

use MiladRahimi\PHPRouter\Exceptions\HttpError;
use MiladRahimi\PHPRouter\Router;

$router = new Router();

$router->get("/", 'App\Controllers\HomeController@index');


try {
//dispatching routes
    $router->dispatch();

} catch(HttpError $e) {

    if($e->getMessage() == "404"){
        $router->publish("Page not found, 404 error!");
    }

} catch(\Exception $e) {
    $router->publish("Sorry, there is an internal error, we will fix it asap!");
}