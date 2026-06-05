<?php
declare(strict_types=1);

// On charge l'autoloader pour avoir accès à la base de données
spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', '/', $class);
    $file = __DIR__ . '//' . $classPath . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use config\Database;

try {
    $pdo = Database::getConnection();

    // 1. On prépare la requête d'insertion
    // (Ajuste les noms des colonnes selon ta table 'users')
    $sql = "INSERT INTO users (firstname, lastname, email, password, phone, role) 
            VALUES (:firstname, :lastname, :email, :password,:phone, :role)";

    $stmt = $pdo->prepare($sql);

    // 2. Définition des utilisateurs à créer
    $usersToInsert = [
        [
            'firstname'  => 'badr',
            'lastname'   => 'belabrik',
            'email'      => 'badr.belabrik@gmail.com',
            'password'   => password_hash('1234', PASSWORD_BCRYPT), // HACHAGE ICI !
            'phone' => '06567784343',
            'role'     => 'patient'
        ],
        [
            'firstname'  => 'yahya',
            'lastname'   => 'bahou',
            'email'      => 'yahya@gmail.com',
            'password'   => password_hash('1234', PASSWORD_BCRYPT),
            'phone' => '0684563022',
            'role' => 'doctor'

        ],
        [
            'firstname'  => 'hassan',
            'lastname'   => 'yacoubi',
            'email'      => 'hassan@gmail.com',
            'password'   => password_hash('1234', PASSWORD_BCRYPT),
            'phone' => '0683993265',
            'role' => 'admin'
        ]
    ];

    // 3. Exécution de l'insertion
    foreach ($usersToInsert as $user) {
        $stmt->execute($user);
        echo "Utilisateur {$user['firstname']} créé avec succès !<br>";
    }

} catch (\PDOException $e) {
    echo "Erreur d'insertion : " . $e->getMessage();
}