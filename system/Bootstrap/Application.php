<?php
declare(strict_types=1);

namespace Zero\System\Bootstrap;

use Zero\System\Exceptions\Whoops;
use Zero\System\File\File;
use Zero\System\Http\Request;
use Zero\System\Http\Response;
use Zero\System\Router\Dispatch;
use Zero\System\Router\Route;
use Zero\System\Session\Session;

class Application 
{    
    public static function run()
    {
        // Register Whoops error endler
        Whoops::handle();
        // Start sesion
        Session::start();
        // Handle the request
        Request::handle();
        // Require all routes directory
        File::require_directory('app/routes');
        // Dispatch all routes
        $data = Dispatch::handle(Route::routes(), Request::url(), Request::method());
        // Handle the response
        echo Response::output($data);
    }
}
