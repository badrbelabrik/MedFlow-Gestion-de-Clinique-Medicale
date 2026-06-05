<?php
namespace config;


use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function getConnection()
    {
        if(self::$pdo === null){


            $envPath = __DIR__ . '/../.env';
            
            if (!file_exists($envPath)) {
                die("Connection failed: The .env file was not found at " . $envPath);
            }

            $env = parse_ini_file($envPath);

            $env = parse_ini_file(__DIR__ . '/../.env');

            $host = $env['DB_HOST'];
            $dbname = $env['DB_NAME'];
            $user = $env['DB_USER'];
            $password = $env['DB_PASSWORD'];

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