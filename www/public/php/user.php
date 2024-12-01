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

    // Suppression d'un utilisateur
    if (isset($_POST['delete-user'])) {
        $userId = (int)$_POST['id-user'];

        $deleteQuery = $db->prepare("DELETE FROM users WHERE id = :id");
        $deleteQuery->execute(['id' => $userId]);
    }

    // Modification d'un utilisateur
    if (isset($_POST['update-user'])) {
        $userId = (int)$_POST['id-user'];
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);

        $updateQuery = $db->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
        $updateQuery->execute(['username' => $username, 'email' => $email, 'id' => $userId]);

    }

    // Récupération de la liste des utilisateurs
    $query = $db->prepare("SELECT id, username, email FROM users");
    $query->execute();
    $users = $query->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href=".\..\css\header.css">
</head>
<body>
    <?php include './include/header.php'; ?>

    <h1>Gestion des utilisateurs</h1>

    <?php if ($users): ?>
        <table>
            <tr>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <form method="POST" style="display: inline;">
                        <!-- Modifier un utilisateur -->
                        <td>
                            <input type="text" name="username" value="<?= htmlspecialchars($user->username) ?>" required>
                        </td>
                        <td>
                            <input type="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
                        </td>
                        <td>
                            <input type="hidden" name="id-user" value="<?= $user->id ?>">
                            <button type="submit" name="update-user" class="update-user">Modifier</button>
                        </td>
                    </form>
                    <td>
                        <!-- Supprimer un utilisateur -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id-user" value="<?= $user->id ?>">
                            <button type="submit" name="delete-user" class="delete-user" >Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucun utilisateur trouvé.</p>
    <?php endif; ?>
</body>
</html>
