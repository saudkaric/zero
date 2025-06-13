<?php
declare(strict_types=1);

namespace Zero\System\Router;

use Zero\System\Url\Url;
use ReflectionException;
use BadFunctionCallException;
use InvalidArgumentException;
use Zero\System\Http\Response;

class Dispatch 
{    
    public static function handle(array $routes, string $uri, string $method)
    {                
        foreach ($routes[$method] as $route)
        {
            $path = ltrim($route['path'], '/') ?: '/';

            $pattern = preg_replace('#{[\w-]+}#', '([\w-]+)', $path);

            if ( preg_match('#^' . $pattern . '$#', $uri, $urlMatches)) 
            {
                preg_match_all('#{([\w-]+)}#',  $path, $routMatches);
                array_shift($urlMatches);
                $args = array_combine($routMatches[1], $urlMatches) ?? [];
                
                return self::invoke($route, $args);
            }
        }
        
        return self::notFound();
    }
    
    protected static function invoke(array $route, array $args = [])
    {
        self::executeMiddleware($route);
        
        $callback = $route['callback'];
                
        if(is_callable($callback)) {
            return call_user_func_array($callback, $args);
        }
        
        if (is_string($callback)) {
            if(strpos($callback, '@') == false) {
                return self::callbackNotFound();
            } else {
                list($object, $method) = explode('@', $callback);
                $object = 'Zero\Controllers\\' . ucfirst($object) . 'Controller';
            }
        }
        
        if (is_array($callback)) {
            if (empty($callback) || empty($callback[0]) || empty($callback[1])) {
                return self::callbackNotFound();
            } else {
                $object = $callback[0];
                $method = $callback[1];
            }
        }
                
        if (! class_exists($object)) {
            
            return self::classNotFound($object);
        }
        
        $class = new $object;
            
        if (! method_exists($class, $method)) {
            return self::methodNotFound($method, $object);
        }
        
        return call_user_func_array([$class, $method], $args);
    }
    
    protected static function executeMiddleware(array $route)
    {
        foreach (explode("|", $route['middleware']) as $middleware)
        {
            if ($middleware != '') {
                
                $class = 'Zero\Middlewares\\' . ucfirst($middleware) . 'Middleware';
            
                if (! class_exists($class)) {
                    return self::classNotFound($class);
                }

                $callback   = new $class();
                $method     = 'handle';
                $args       = [];

                if (! method_exists($callback, $method)) {
                    return self::methodNotFound($method, $class);
                }

                call_user_func_array([$callback, $method], $args);
            }
            
        }
    }
    
    protected static function notFound(): string
    {
        return Url::redirect(Url::path('not-found')); 
    }
    
    protected static function classNotFound(string $class)
    {
        throw new ReflectionException(
            sprintf("Class '%s' not found", $class)
        );        
    }
    
    protected static function methodNotFound(string $method, string $class)
    {
        throw new BadFunctionCallException(
            sprintf("Action method '%s' does not existst at Class: '%s'", $method, $class)
        );
    }
    
    protected static function callbackNotFound()
    {
        throw new InvalidArgumentException("Please provide valid callback function");
    }
    
}
