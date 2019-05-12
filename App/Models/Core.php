<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 12.05.2019
 * Time: 20:11
 */

/**
 * Class Core
 */
abstract class Core
{
    public function __construct()
    {
    }

    /**
     * @return PDO|null
     * TODO: make single connection (like a singletone)
     */
    protected static function getDB()
    {
        static $db = null;
        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $db;
    }
}