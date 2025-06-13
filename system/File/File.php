<?php
declare(strict_types=1);

namespace Zero\System\File;

class File 
{
    public static function root(): string
    {
        return ROOT_DIR;        
    }
    
    public static function ds(): string
    {
        return DS;        
    }
    
    public static function path(string $path): string 
    {
        return str_replace(['/', '\\'], self::ds(), self::root() . trim($path, '/'));
    }
    
    public static function exist(string $path): bool
    {
        return file_exists(self::path($path));
    }
    
    public static function require_file(string $path): mixed
    {
         if (self::exist($path)) {
             return require self::path($path);
         }
         
         return false;
    }
    
    public static function include_file(string $path): mixed
    {
         if (self::exist($path)) {
             return include self::path($path);
         }
         
         return false;
    }
    
    public static function require_once_file(string $path): mixed
    {
         if (self::exist($path)) {
             return require_once self::path($path);
         }
         
         return false;
    }
    
    public static function include_once_file(string $path): mixed
    {
         if (self::exist($path)) {
             return include_once self::path($path);
         }
         
         return false;
    }
    
    public static function require_directory(string $path): void 
    {
        $files = array_diff(scandir(self::path($path)), ['.', '..']);
        
        foreach ($files as $file)
        {            
            self::require_once_file($path . self::ds() . $file);
        }
    }
    
    
    
}
