<?php
declare(strict_types=1);

namespace Zero\Controllers;

use Zero\Models\User;

class PageController 
{
    public function index()
    {        
        return view('pages.index');
    }
    
    
}
