<?php

namespace dao;


class Dao{
    private static $host = 'localhost';
    private static $dbname = 'db';
    private static $username = 'root';
    private static $password = '';

    public static function getConnection() {
        try {
            $pdo = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname, self::$username, self::$password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
