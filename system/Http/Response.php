<?php

declare(strict_types=1);

namespace Zero\System\Http;

class Response 
{
    public static function output(mixed $data)
    {
        if (! $data) return;
        
        if (!is_string($data)) {
            
            $data = self::json($data);
        }
        
        return  $data;
    }
    
    public static function json(mixed $data)
    {
        self::contentType('application/json');
        return json_encode($data);
    }
    
    public static function code(int|string $code = 200)
    {
        return http_response_code((int)$code);
    }
    
    public static function contentType(string $type = 'text/html')
    {
        return header('Content-Type: ' . $type);
    }
}
