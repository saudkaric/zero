<?php
declare(strict_types=1);

namespace Zero\System\Database;

use Zero\System\Database\Database;

class Crud extends Database
{
    
    // CREATE
    public static function insert(array $data): object
    {
        $table = static::$table;
        $query = "INSERT INTO {$table} SET ";
        
        static::execute($data, $query);
        
        $obj_id = static::$connection->lastInsertId();
        $obj = self::table($table)->where('id', $obj_id)->get();
        
        return $obj;
    }
    
    // READE
    public static function getAll(): object|array
    {
        return static::fetchExecute()->fetchAll();
    }
    
    public static function get(): object|array
    {        
        return static::fetchExecute()->fetch();
    }
    
    // UPDATE
    public static function update(array $data): bool
    {
        $query = "UPDATE " . static::$table . " SET ";
        
        static::execute($data, $query, true);
        
        return true;
    }
    
    // DELETE
    public static function delete(): bool
    {
        $query = "DELETE FROM " . static::$table;
        
        static::execute([], $query, true);
        
        return true;
    }
}
