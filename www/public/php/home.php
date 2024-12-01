<?php
    session_start(); 

    // Vérifie si l'utilisateur est connecté
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username']; // Récupère le username depuis la session
    } else {
        $username = "Invité"; // Valeur par défaut si l'utilisateur n'est pas connecté
    }

    require_once './../../entities/database.php';
    require_once './../../entities/User.php';

    $db = connect();
    $query = $db->prepare("SELECT id, username, email, password, created_at FROM users");
    $query->execute();
    $users = $query->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formulaire des utilisateurs </title>
        <link rel="stylesheet" href=".\..\css\header.css">
    </head>
    <body>
    <?php include './include/header.php'; ?>
    </body>
</html>