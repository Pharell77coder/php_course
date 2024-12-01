<?php
session_start(); 

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header('Location: ./../../index.php'); // Redirige vers le formulaire si non connecté
    exit();
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
        <link rel="stylesheet" href="public/css/style.css">
    </head>
    <body>
    <h1>PHP Test Page</h1>
    <div class="welcome-box">
        <h1>Bienvenue, <?= htmlspecialchars($username); ?> !</h1>
        <p>Vous êtes connecté avec succès.</p>
        <a href="./../../entities/logout.php">Déconnexion</a>
    </div>
    <?php if ($users): ?>
        <table>
            <tr>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Modification</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <form class="form-users" method="POST">
                    <tr>
                        <td><?= $user->username ?></td>
                        <td><?= $user->email ?></td>
                        <td>
                            <button class="update-user" name="update-user">Modifier</button>
                            <button class="delete-user" name="delete-user">Supprimer</button>
                        </td>
                    </tr>
                </form>
            <?php endforeach;?>
        </table>
    <?php endif;?>
    </body>
</html>