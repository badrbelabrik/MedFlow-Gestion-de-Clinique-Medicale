<?php

namespace Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function getConnection()
    {
        if(self::$pdo === null){

            // read .env
            $config = parse_ini_file(__DIR__ . '/../.env');

            $host = $config['DB_HOST'];
            $dbname = $config['DB_NAME'];
            $user = $config['DB_USER'];
            $password = $config['DB_PASSWORD'];

            try{

                self::$pdo = new PDO(
                    "mysql:host=$host;dbname=$dbname",
                    $user,
                    $password
                );

                self::$pdo->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION
                );

            }catch(PDOException $e){

                die("Connection failed : " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}