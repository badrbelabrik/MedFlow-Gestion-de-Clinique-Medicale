<?php

namespace Repositories;

use config\Database;
use PDO;
use PDOException;

class SpecialityRepository
{
    private PDO $pdo;
    public function __construct(){
        $this->pdo = Database::getConnection();
    }

    public function getAllSpecialities():array{
        try{
            $sql = "SELECT * FROM specialities";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo "Error :".$e->getMessage();
            return [];
        }
    }
}