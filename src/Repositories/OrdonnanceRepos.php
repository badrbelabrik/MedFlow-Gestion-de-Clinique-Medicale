<?php
namespace Repositories;
require_once __DIR__ . '/../../config/database.php';

use Config\Database;

class OrdonnanceRepos {

    public function sauvegarder($description, $id_rdv) {
        $pdo = Database::getConnection();


        $check = $pdo->prepare("SELECT id FROM appointments WHERE id = ?");
        $check->execute([$id_rdv]);
        $existe = $check->fetch();

        if (!$existe) {
            $getRealId = $pdo->query("SELECT id FROM appointments LIMIT 1");
            $realRDV = $getRealId->fetch();

            if ($realRDV) {
                $id_rdv = $realRDV['id']; 
            } else {
                die("<div style='background:#1e2640; color:#fff; padding:20px; font-family:sans-serif; border-radius:10px; margin:20px;'>
                        <h3 style='color:#f43f5e'>قاعدة البيانات خاوية!</h3>
                        <p>جدول <b>appointments</b> ما فيه حتى شي موعد دابا.</p>
                     </div>");
            }
        }

  
        $sql = "INSERT INTO prescriptions (description, id_appointment) VALUES (:description, :id_appointment)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'description' => $description,
            'id_appointment' => $id_rdv
        ]);
    }
}