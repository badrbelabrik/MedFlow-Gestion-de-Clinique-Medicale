<?php

require_once __DIR__ . '/../../config/database.php';

class MedecinRepository {

    public function findAll(): array {

        return getDB()->query("
            SELECT
                d.id,
                u.firstname,
                u.lastname,
                u.email,
                u.phone,
                d.is_active,
                s.name AS speciality,
                d.id_user,
                d.id_speciality
            FROM doctors d
            JOIN users u ON u.id = d.id_user
            JOIN specialities s ON s.id = d.id_speciality
            ORDER BY u.lastname
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array {

        $stmt = getDB()->prepare("
            SELECT
                d.id,
                u.firstname,
                u.lastname,
                u.email,
                u.phone,
                d.is_active,
                s.name AS speciality,
                d.id_user,
                d.id_speciality
            FROM doctors d
            JOIN users u ON u.id = d.id_user
            JOIN specialities s ON s.id = d.id_speciality
            WHERE d.id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): void {

        $db = getDB();

        $db->beginTransaction();

        try {

            $stmt = $db->prepare("
                INSERT INTO users(
                    firstname,
                    lastname,
                    email,
                    password,
                    phone,
                    role
                )
                VALUES(?,?,?,?,?,'doctor')
            ");

            $stmt->execute([
                $data['firstname'],
                $data['lastname'],
                $data['email'],
                password_hash($data['password'], PASSWORD_BCRYPT),
                $data['phone']
            ]);

            $userId = $db->lastInsertId();

            $stmt = $db->prepare("
                INSERT INTO doctors(
                    id_user,
                    id_speciality
                )
                VALUES(?,?)
            ");

            $stmt->execute([
                $userId,
                $data['id_speciality']
            ]);

            $db->commit();

        } catch (Exception $e) {

            $db->rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): void {

        $doctor = $this->findById($id);

        if (!$doctor) {
            throw new Exception("Doctor introuvable");
        }

        $db = getDB();

        $db->beginTransaction();

        try {

            $db->prepare("
                UPDATE users
                SET firstname=?,
                    lastname=?,
                    email=?,
                    phone=?
                WHERE id=?
            ")->execute([
                $data['firstname'],
                $data['lastname'],
                $data['email'],
                $data['phone'],
                $doctor['id_user']
            ]);

            $db->prepare("
                UPDATE doctors
                SET id_speciality=?
                WHERE id=?
            ")->execute([
                $data['id_speciality'],
                $id
            ]);

            $db->commit();

        } catch (Exception $e) {

            $db->rollBack();
            throw $e;
        }
    }

    public function toggleActif(int $id): void {

        $doctor = $this->findById($id);

        if (!$doctor) {
            throw new Exception("Doctor introuvable");
        }

        $newValue = $doctor['is_active'] ? 0 : 1;

        getDB()->prepare("
            UPDATE doctors
            SET is_active = ?
            WHERE id = ?
        ")->execute([
            $newValue,
            $id
        ]);
    }
}