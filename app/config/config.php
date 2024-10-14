<?php

class Database {
    const HOST = 'localhost';
    const PORT = 3306;
    const DBNAME = 'test_kalstein';
    const DBUSER = 'root';
    const DBPASS = '';
    const CHARSET = 'utf8';

    private static $pdo = null;

    public static function connect() {
        if (self::$pdo === null) {
            $dsn = 'mysql:host=' . self::HOST . ';port=' . self::PORT . ';dbname=' . self::DBNAME . ';charset=' . self::CHARSET;
            try {
                self::$pdo = new PDO($dsn, self::DBUSER, self::DBPASS);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Connection failed: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
?>