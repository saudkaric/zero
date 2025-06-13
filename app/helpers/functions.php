<?php

declare(strict_types=1);

function show($value, $die = false): mixed
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    $die == true ? die() : '';
}

function view(string $path, array $data = [], string $type = 'blade'): string
{
    return Zero\System\View\View::render($path, $data, $type);    
}

function request(string $key): ?string
{
    return \Zero\System\Http\Request::value($key);
}

function request_get(string $key): ?string
{
    return \Zero\System\Http\Request::value($key, $_GET);
}

function request_post(string $key): ?string
{
    return \Zero\System\Http\Request::value($key, $_POST);
}

function redirect(string $url): string
{
    return \Zero\System\Url\Url::redirect($url);    
}

function previous(): string
{
    return \Zero\System\Url\Url::previous();
}

function url(string $path): string
{
    return \Zero\System\Url\Url::path($path);    
}

function asset(string $path): string
{
    return \Zero\System\Url\Url::path('asset/'.$path);    
}

function session(string $key): string 
{
    return Zero\System\Session\Session::get($key);    
}

function flash(string $key): string
{
    return Zero\System\Session\Session::flash($key);    
}

function auth(string $key): string
{
    return Zero\System\Session\Session::get($key) ?: Zero\System\Cookie\Cookie::get($key);    
}