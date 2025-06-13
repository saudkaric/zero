<?php
declare(strict_types=1);

namespace Zero\System\Router;

use BadFunctionCallException;

class Route 
{
    protected static array  $routes      = [];
    protected static string $middleware  = '';
    protected static string $prefix      = '';
    
    private static function add(string $uri, mixed $callback, string $methods): void
    {
        $uri = rtrim(self::$prefix . '/' . trim($uri, '/'), '/');
        
        foreach (explode('|', $methods) as $method)
        {
            self::$routes[$method][$uri] = [
                'path'       => $uri,
                'method'     => $method,
                'callback'   => $callback,
                'middleware' => self::$middleware
            ];
        }
    }

    public static function get(string $uri, mixed $callback): void
    {
        self::add($uri, $callback, 'GET');
    }
    
    public static function post(string $uri, mixed $callback): void
    {
        self::add($uri, $callback, 'POST'); 
    }
    
    public static function put(string $uri, mixed $callback): void
    {
        self::add($uri, $callback, 'PUT'); 
    }
    
    public static function patch(string $uri, mixed $callback): void
    {
        self::add($uri, $callback, 'PATCH'); 
    }
    
    public static function delete(string $uri, mixed $callback): void
    {
        self::add($uri, $callback, 'DELETE'); 
    }
    
    public static function any(string $uri, mixed $callback): void
    {
        self::add($uri, $callback, 'GET|POST|PUT|PATCH|DELETE'); 
    }
    
    public static function notFound(mixed $callback = 'error@notFound', string $uri = 'not-found'): void
    {
        self::add($uri, $callback, 'GET');
    }
    
    public static function prefix(string $prefix, mixed $callback): void
    {
        $parent_prefix = self::$prefix;
        
        self::$prefix .= '/' . trim($prefix, '/');
        
        if (is_callable($callback)) {
            call_user_func($callback);
        } else {
            throw new BadFunctionCallException('Please provide valid callback function');
        }
        self::$prefix = $parent_prefix;
    }
    
    public static function middleware(string $middleware, mixed $callback): void
    {
        $parent_middleware = self::$middleware;
        
        self::$middleware .= '|' . trim($middleware, '|');
        
        if (is_callable($callback)) {
            call_user_func($callback);
        } else {
            throw new BadFunctionCallException('Please provide valid callback function');
        }
        self::$middleware = $parent_middleware;
    }
    
    public static function routes(): array
    {   
        return self::$routes;
    }
}
