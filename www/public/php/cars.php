<?php
session_start(); 

// Vérifie si l'utilisateur est connecté
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Récupère le username depuis la session
} else {
    $username = "Invité"; // Valeur par défaut si l'utilisateur n'est pas connecté
}

require_once './../../entities/database.php';
require_once './../../entities/Cars.php';

$db = connect();
$query = $db->prepare("SELECT id, model, brand, price, build_at FROM cars");
$query->execute();
$cars = $query->fetchAll(PDO::FETCH_OBJ);

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tableau des voitures </title>
        <link rel="stylesheet" href="public/css/style.css">
    </head>
    <body>
    <h1>PHP Test Page</h1>
    <div class="welcome-box">
    <h1><?= htmlspecialchars($username); ?></h1>
    <a href="./../../entities/logout.php">Déconnexion</a>
    </div>
    <?php if ($cars): ?>
        <table>
            <tr>
                <th>model</th>
                <th>brand</th>
                <th>price</th>
                <th>build_at</th>
                <th>Modification</th>
            </tr>
            <?php foreach ($cars as $car): ?>
                <form class="form-users" method="POST">
                    <tr>
                        <td><?= $car->model ?></td>
                        <td><?= $car->brand ?></td>
                        <td><?= $car->price ?></td>
                        <td><?= $car->build_at ?></td>
                        <td>
                            <button class="update-car" name="update-car">Modifier</button>
                            <button class="delete-car" name="delete-car">Supprimer</button>
                        </td>
                    </tr>
                </form>
            <?php endforeach;?>
        </table>
    <?php endif;?>
    </body>
</html>