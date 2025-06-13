<?php
declare(strict_types=1);

namespace Zero\System\Cookie;

class Cookie 
{
    public static  function set(string $key, string $value): string
    {
        setcookie($key, $value, strtotime('+5 days'), '/', '', false, true);
        
        return $value;
    }
    
    public static function has(string $key): bool
    {
        return isset($_COOKIE[$key]);
    }
    
    public static  function get(string $key): ?string
    {
        return self::has($key) ? $_COOKIE[$key] : null;
    }
    
    public static  function delete(string $key): void
    {
        setcookie($key, '', -1, '/');
    }
    
    public static function destroy(): void
    {
        foreach (self::all() as $key => $value)
        {
            self::delete($key);
        }
    }
    
    public static function all(): array
    {
        return $_COOKIE;        
    }
}
