<?php
declare(strict_types=1);

namespace Zero\System\Http;

class Request
{
    protected static string $url            = '';
    protected static string $base_url       = '';
    protected static string $full_url       = '';
    protected static string $query_string   = '';
    protected static string $script_name    = '';
    
    public static function handle(): void
    {
        self::$script_name = str_replace('\\', '', dirname(Server::get('SCRIPT_NAME')));
        self::setBaseUrl();
        self::setUrl();
    }
    
    private static function setBaseUrl(): void
    {
        $protocol = Server::get('REQUEST_SCHEME') . '://';
        $host = Server::get('HTTP_HOST');
        $script_name = self::$script_name;
        
        self::$base_url = $protocol . $host . $script_name;
    }
    
    private static function setUrl(): void
    {        
        $requst_uri = trim(preg_replace("#^" . self::$script_name . "#", "", urldecode(Server::get('REQUEST_URI'))), '/');
        $query_string = '';
        
        self::$full_url = $requst_uri;
        
        if (strpos($requst_uri, '?') !== false) {
            list($requst_uri, $query_string) = explode('?', $requst_uri);
        }
        
        self::$url = $requst_uri ?:'/';
        self::$query_string = $query_string;
    }
    
    public static function baseUrl(): string
    {
        return rtrim(self::$base_url, '/');        
    }
    
    public static function fullUrl(): string
    {
        return self::$full_url;        
    }
    
    public static function url(): string
    {
        return self::$url;        
    }
    
    public static function queryString(): string
    {
        return self::$query_string;        
    }
    
    public static function method(): string 
    {
        return Server::get('REQUEST_METHOD');        
    }
    
    public static function get(string $key): ?string 
    {
        return self::value($key, $_GET);        
    }
    
    public static function post(string $key): ?string
    {
        return self::value($key, $_POST);        
    }
    
    public static function has(array $type, string $key): bool
    {
        return array_key_exists($key, $type);
    }
    
    public static function value(string $key, array $type = null): ?string
    {
        $type = isset($type) ? $type : $_REQUEST;
        
        return self::has($type, $key) ? $type[$key] : null;
    }
    
    public static function set(string $key, string $value): string
    {
        $_REQUEST[$key] = $value;        
        $_POST[$key] = $value;        
        $_GET[$key] = $value;        
        
        return $value;
    }
    
    public static function previous(): string 
    {
        return Server::get('HTTP_REFERER');        
    }
    
    public static function all(): array
    {
        return $_REQUEST;        
    }
}
