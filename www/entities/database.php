<?php

function connect(){
    $host = 'mariadb';
    $db = 'database';
    $user = 'admin';
    $pass = 'password';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        echo "Connexion réussie à MariaDB !";
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
    
    return $pdo;
}
?>