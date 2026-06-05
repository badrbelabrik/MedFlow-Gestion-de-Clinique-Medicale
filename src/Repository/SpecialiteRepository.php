<?php

require_once __DIR__ . '/../../config/database.php';

class SpecialiteRepository {

    public function findAll(): array {

        return getDB()->query("
            SELECT *
            FROM specialities
            ORDER BY name
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array {

        $stmt = getDB()->prepare("
            SELECT *
            FROM specialities
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(string $name, string $description): void {

        $stmt = getDB()->prepare("
            INSERT INTO specialities(name, description)
            VALUES (?, ?)
        ");

        $stmt->execute([
            $name,
            $description
        ]);
    }

    public function update(
        int $id,
        string $name,
        string $description
    ): void {

        $stmt = getDB()->prepare("
            UPDATE specialities
            SET name = ?,
                description = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $name,
            $description,
            $id
        ]);
    }

    public function delete(int $id): void {

        $stmt = getDB()->prepare("
            SELECT COUNT(*)
            FROM doctors
            WHERE id_speciality = ?
        ");

        $stmt->execute([$id]);

        if ($stmt->fetchColumn() > 0) {
            throw new Exception(
                'Impossible : des médecins utilisent cette spécialité.'
            );
        }

        getDB()->prepare("
            DELETE FROM specialities
            WHERE id = ?
        ")->execute([$id]);
    }
}