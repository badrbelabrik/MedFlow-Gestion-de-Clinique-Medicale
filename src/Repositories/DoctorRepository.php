<?php
namespace Repositories;
use PDO;

class DoctorRepository {
    private $db;
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    public function getDoctorById($id) {
        $id = $_SESSION['user_id'];
        $stmt = $this->db->prepare("SELECT users.firstname , users.lastname , appointments.status  from appointments 
join users on appointments.id_patient=users.id
WHERE id_doctor=?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

?>