<?php

namespace Repositories;

use Config\Database;
use Doctor;
use PDO;
use PDOException;
use User;

class UserRepository
{
    private PDO $pdo;

    public function __construct(){
        $this->pdo = Database::getConnection();
    }

    public function getUserById(int $userId):?User{
        try{
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return new User(
                    $result['firstname'],
                    $result['lastname'],
                    $result['email'],
                    $result['phone'],
                    $result['role'],
                    $result['id']
                );
        }catch(PDOException $e){
            echo "Error :".$e->getMessage();
            return null;
        }
    }
}