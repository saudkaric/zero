<?php
declare(strict_types=1);

namespace Zero\System\Http;

class Server 
{
    public static function all(): array 
    {
        return $_SERVER;        
    }
    
    public static function has(string $key): bool 
    {
        return isset($_SERVER[$key]);
    }
    
    public static function get(string $key): ?string 
    {
        return self::has($key) ? $_SERVER[$key] : null;
    }
    
    public static function path_into(string $path): array 
    {
        return pathinfo($path);                
    }
    
}
