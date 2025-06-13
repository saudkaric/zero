<?php

declare(strict_types=1);

namespace Zero\System\Exceptions;

class Whoops 
{
    public static function handle()
    {
        switch(ENV)
        {
            case 'development';
                $whoops = new \Whoops\Run();
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
                $whoops->register();
                break;
            case 'testing';
                error_reporting(E_ALL);
                ini_set("display_errors", 1);
                break;
            default;
                if (DEBUG) {
                    // Turn off all error reporting
                    echo 'not sure what happened there. please try again later';
                    exit(1);
                } else {
                    // Turn off all error reporting
                    error_reporting(0);
                    ini_set('display_errors','Off');
                    ini_set('error_reporting', E_ALL );
                }
                break;
        }
        
        
    }
}
