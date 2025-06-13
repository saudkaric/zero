<?php
declare(strict_types=1);

namespace Zero\System\View;

use Exception;
use Jenssegers\Blade\Blade;
use Zero\System\File\File;
use Zero\System\Session\Session;

class View 
{
    
    public static function render(string $path, array $data = [], $type = 'blade'): string
    {
        $old    = Session::flash('old');
        $errors = Session::flash('errors');
        
        $data   = array_merge($data, ['errors' => $errors, 'old'=> $old]);
        
        $render = strtolower($type) . 'Render';
        return self::$render($path, $data);
    }
    
    public static function bladeRender(string $path, array $data = []): string 
    {
        $blade = new Blade(
                File::path('app/views'),
                File::path('app/storage/cache')
        );
        return $blade->make($path, $data)->render();
    }
    
    public static function contentRender(string $path, array $data = []): string
    {
        $path = 'app' . File::ds() .'views' . File::ds() . 
                str_replace(['.', '@', '/', '|'], File::ds(), $path) . '.php';
        
        if (! File::exist($path)) {
            throw new Exception(
                sprintf('The view "%s" does not exists', $path)
            );
        }
        
        ob_start();
        extract($data);
        
        include File::path($path);
        
        $content = ob_get_contents();
        
        ob_get_clean();
        
        return $content;
    }
}
