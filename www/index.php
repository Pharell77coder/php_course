<?php
require_once './entities/database.php';
require_once './entities/User.php';

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
    </head>
    <body>
    <h1>PHP Test Page</h1>

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

    <form id="form_user" method="POST">
        <label for="username">Pseudo :</label>
        <input type="text" id="username" name="username" placeholder="Nom d'utilisateur...">

        <label for="email">Email :</label>
        <input type="text" id="email" name="email" placeholder="Adresse mail...">

        <label for="password">Mot de passe :</label>
        <input type="text" id="password" name="password" placeholder="Mot de passe...">

        <input type="submit" name="insert-user" id="submit-btn" value="Envoyer">
    </form>
    </body>
</html>