<?php
declare(strict_types=1);

namespace Zero\Controllers;

use Zero\System\Http\Response;

class ErrorController 
{
    //put your code here
    public function notFound()
    {
        Response::code(404);
        return \Zero\System\View\View::render('errors.404');
    }
}
