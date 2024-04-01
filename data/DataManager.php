<?php

require_once __DIR__ . '/Database.php';

class DataManager
{
    private static array $db = [];
    
    public static function getDatabase(string $modelClass): Database
    {
        if (!isset(self::$db[$modelClass])) {
            self::$db[$modelClass] = new Database($modelClass);
        }
        return self::$db[$modelClass];
    }

    public static function getAllDatabases(): array
    {
        return self::$db;
    }
}
