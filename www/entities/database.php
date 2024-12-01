<?php

function connect(){
    // Configuration de la connexion MariaDB
    $host = 'mariadb';
    $db = 'database';
    $user = 'admin';
    $pass = 'password';

    try {
        // Connexion à la base de données
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
    
    return $pdo;
}
?>