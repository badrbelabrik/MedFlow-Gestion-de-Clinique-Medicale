<?php

namespace Repositories;

use config\Database;
use Entities\Doctor;
use Entities\User;
use PDO;
use PDOException;


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

    public function verifyLogin($email,$password):?User
    {
        try{
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$email]);
            $userData = $stmt->fetch();

            if(!$userData){
                return null;
            }

            if(password_verify($password,$userData['password'])){
                return new User(
                    $userData['firstname'],
                    $userData['lastname'],
                    $userData['email'],
                    $userData['password'],
                    $userData['phone'],
                    $userData['role'],
                    $userData['id']
                );
            }
            return null;
        }catch(PDOException $e){
            echo "Error: ".$e->getMessage();
            return null;
        }
    }
}