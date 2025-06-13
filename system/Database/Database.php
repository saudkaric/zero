<?php
declare(strict_types=1);

namespace Zero\System\Database;

use PDO;
use Exception;
use PDOException;
use Zero\System\File\File;
use Zero\System\Database\Crud;

class Database extends Crud
{
    protected static string $select     = '';
    protected static string $table      = '';
    protected static string $join       = '';
    protected static string $where      = '';
    protected static string $group_by   = '';
    protected static string $having     = '';
    protected static string $order_by   = '';
    protected static string $limit      = '';
    protected static string $offset     = '';
    protected static string $setters    = '';
    protected static string $query      = '';
    protected static array  $binding        = [];
    protected static array  $where_binding  = [];
    protected static array  $having_binding = [];
    
    protected static ?Database $instance    = null;
    protected static ?PDO $connection       = null;
    
    public function __construct() {}
    
    private static function connect(): void
    {
        if (!static::$connection) {
            
            extract(File::require_file('app/config/database.php'));
            
            $dsn = 'mysql:host=' . $host .';dbname=' . $dbname;
                        
            $options = [
                PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_OBJ,
                PDO::ATTR_PERSISTENT            => false,
                PDO::MYSQL_ATTR_INIT_COMMAND    => "set NAMES {$charset} COLLATE {$collation}",
            ];
            
            try {
                static::$connection = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                throw new Exception("Failed to connect to the database: {$e->getMessage()}");
            }
            
        }
    }
    
    private static function instance(): Database
    {
        static::connect();
        
        if (! self::$instance) {
            self::$instance = new Database();
        }
        
        return self::$instance;
    }
    
    private static function clear(): void
    {
       static::$instance   = null;
       static::$query      = '';
       static::$select     = '';
       static::$table      = '';
       static::$join       = '';
       static::$where      = '';
       static::$group_by   = '';
       static::$having     = '';
       static::$order_by   = '';
       static::$limit      = '';
       static::$offset     = '';
       static::$setters    = '';
       static::$binding        = [];
       static::$where_binding  = [];
       static::$having_binding = [];
    }
    
    public static function query(string $query = null): Database 
    {
        static::instance();
        
        if ($query == null) {
            
            if (static::$table == '') {
                throw new Exception('Table name unkown!');
            }
            
            $query  = 'SELECT ';
            $query .= static::$select ?:'*';
            $query .= ' FROM ' . static::$table      . ' ';
            $query .= static::$join       . ' ';
            $query .= static::$where      . ' ';
            $query .= static::$group_by   . ' ';
            $query .= static::$having     . ' ';
            $query .= static::$order_by   . ' ';
            $query .= static::$limit      . ' ';
            $query .= static::$offset     . ' ';
        }
        
        static::$query = $query;
        static::$binding = array_merge(static::$where_binding, static::$having_binding);
        
        return static::instance();
    }
    
    public static function select(): Database 
    {
        static::$select = implode(',', func_get_args());
        
        return static::instance();
    }
    
    public static function table(string $name): Database  
    {
        static::$table = $name;
        
        return static::instance();
    }
    
    public static function join(
            string $table, 
            string $first, 
            string $second, 
            string $operator = '=', 
            string $type= 'INNER'): Database 
    {
        static::$join .= " {$type} JOIN {$table} ON {$first}{$operator}{$second}";
        
        return static::instance();
    }
    
    public static function joinRight(
            string $table, 
            string $first, 
            string $second, 
            string $operator = '='): Database 
    {
        static::join($table, $first, $second, $operator, 'RIGHT');
        
        return static::instance();
    }
    
    public static function joinLeft(
            string $table, 
            string $first, 
            string $second, 
            string $operator = '='): Database 
    {
        static::join($table, $first, $second, $operator, 'LEFT');
        
        return static::instance();
    }
    
    public static function where(
            string $column,
            string|int $value,
            string $operator = '=',
            string $type = null): Database 
    {
        $where = "`{$column}` {$operator} ?";
        
        if (! static::$where) {
            $stmt = " WHERE {$where}";
        } else {
            if ($type == null) {
                $stmt = " AND {$where}";
            } else {
                $stmt = " {$type} {$where}";
            }
        }
        
        static::$where .= $stmt;
        static::$where_binding[] = htmlspecialchars((string) $value); 
        
        return static::instance();
    }
    
    public static function orWhere(
            string $column,
            string|int $value,
            string $operator = '='): Database
    {
        static::where($column, $value, $operator, 'OR');
        
        return static::instance();
    }
    
    public static function groupBy(): Database
    {
        static::$group_by = "GROUP BY " . implode(', ', func_get_args()) ." ";
        
        return static::instance();        
    }
    
    public static function having(
            string $column,
            string|int $value,
            string $operator = '='): Database 
    {
        $having = "`{$column}` {$operator} ?";
        
        if (! static::$having) {
            $stmt = " HAVING {$having}";
        } else {
            $stmt = " AND {$having}";
        }
        
        static::$having .= $stmt;
        static::$having_binding[] = htmlspecialchars((string) $value); 
        
        return static::instance();
    }
    
    public static function orderBy(string $column, string $type = null) : Database 
    {
        $sep = static::$order_by ? ' , ' : ' ORDER BY ';
        $type = ($type != null && 
                in_array(strtoupper($type), ['ASC', 'DESC'])) 
                ? strtoupper($type) 
                : 'ASC';
        
        static::$order_by .= "{$sep} {$column} {$type}";
        
        return static::instance();
    }
    
    public static function limit(string|int $limit): Database
    {
        static::$limit = "LIMIT {$limit}";
        
        return static::instance();
    }
    
    public static function offset(string|int $offset): Database
    {
        static::$offset = "OFFSET {$offset}";
        
        return static::instance();
    }
    
    protected static function fetchExecute(): object
    {
        static::query(static::$query);
        $query = trim(static::$query, ' ');
        
        $data = static::$connection->prepare($query);
        $data->execute(static::$binding);
                
        static::clear();
        
        return $data;
    }
    
    protected static function execute(array $data, string $query, bool $where = null): void
    {
        static::instance();
        
        if (! static::$table) {
            throw new Exception('Unknow Tabel Name');
        }
        
        if (!empty($data)) {
            foreach ($data as $key => $value)
            {
                static::$setters .= " `{$key}` = ?, ";
                static::$binding[] = filter_var($value, FILTER_SANITIZE_STRING);
            }
        }
        
        static::$setters = trim(static::$setters, ', ');
        
        $query .= static::$setters;
        $query .= $where != null ? static::$where . '' : '';
                
        static::$binding = $where != null ?
                        array_merge(static::$binding, static::$where_binding) :
                        static::$binding;
        
        $data = static::$connection->prepare($query);
        $data->execute(static::$binding);
        
        static::clear();
    }
    
    
    
    
}
