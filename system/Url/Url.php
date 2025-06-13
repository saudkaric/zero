<?php
declare(strict_types=1);

namespace Zero\System\Url;

use Zero\System\Http\Request;

class Url 
{
    public static function path(string $path): string
    {
        return Request::baseUrl() . '/' . trim($path, '/');
    }
    
    public static function previous(): string 
    {
        return Request::previous();
    }
    
    public static function redirect(string $url): string
    {
        header('Location: ' . $url);
        exit;
    }
}
