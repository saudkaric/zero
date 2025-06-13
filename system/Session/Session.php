<?php
declare(strict_types=1);

namespace Zero\System\Session;

class Session 
{
    public static  function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            
            ini_set('session.use_only_cookies', 1);
            
            session_start();
        }
    }
    
    public static  function set(string $key, string $value): string
    {
        $_SESSION[$key] = $value;
        
        return $value;
    }
    
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
    
    public static  function get(string $key): ?string
    {
        return self::has($key) ? $_SESSION[$key] : null;
    }
    
    public static  function delete(string $key): void
    {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
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
        return $_SESSION;        
    }
    
    public static function flash(string $key): string
    {
        $value = '';
        
        if (self::has($key)) {
            $value = self::get($key);
            self::delete($key);
        }
        
        return $value;        
    }
}
