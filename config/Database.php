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

            // FIX: Use __DIR__ to go exactly one level up from the config folder to find .env
            $envPath = __DIR__ . '/../.env';
            
            if (!file_exists($envPath)) {
                die("Connection failed: The .env file was not found at " . $envPath);
            }

            $env = parse_ini_file($envPath);
            // read .env
            $env = parse_ini_file(".env");

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

            }catch(PDOException $e){

                die("Connection failed : " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}