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
    // Récupérer les utilisateurs
    $usersQuery = $db->prepare("SELECT id, username FROM users WHERE username = :username ");
    $usersQuery->execute(['username' => $username]);
    $users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les véhicules
    $carsQuery = $db->query("SELECT id, model FROM cars");
    $cars = $carsQuery->fetchAll(PDO::FETCH_ASSOC);
    if ($users) {
    // Récupérer les associations utilisateurs-voitures
    $carsUserQuery = $db->prepare("
        SELECT cu.id_user, cu.id_car, cu.assigned_at, u.username, c.model 
        FROM cars_users cu
        JOIN users u ON cu.id_user = u.id
        JOIN cars c ON cu.id_car = c.id
        WHERE cu.id_user = :user_id
    ");
    $carsUserQuery->execute(['user_id' => $users[0]['id']]);
    $carsUsers = $carsUserQuery->fetchAll(PDO::FETCH_ASSOC);
}else{
    $carsUsers = null;
}
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




    <?php if ($carsUsers): ?>
            <h2>Associations actuelles</h2>
            <table>
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Voiture</th>
                        <th>Date d'assignement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carsUsers as $carsUser): ?>
                        <tr>
                            <td><?= htmlspecialchars($carsUser['username']) ?></td>
                            <td><?= htmlspecialchars($carsUser['model']) ?></td>
                            <td><?= htmlspecialchars($carsUser['assigned_at']) ?></td>
                            <td>
                                <!-- Modifier une association -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="old_id_user" value="<?= $carsUser['id_user'] ?>">
                                    <input type="hidden" name="old_id_car" value="<?= $carsUser['id_car'] ?>">

                                    <select name="new_user_id" required>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?= $user['id'] ?>" <?= $user['id'] == $carsUser['id_user'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($user['username']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <select name="new_car_id" required>
                                        <?php foreach ($cars as $car): ?>
                                            <option value="<?= $car['id'] ?>" <?= $car['id'] == $carsUser['id_car'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($car['model']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <button type="submit" name="update">Modifier</button>
                                </form>

                                <!-- Supprimer une association -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id_user" value="<?= $carsUser['id_user'] ?>">
                                    <input type="hidden" name="id_car" value="<?= $carsUser['id_car'] ?>">
                                    <button type="submit" name="delete">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune association utilisateur-véhicule trouvée.</p>
        <?php endif; ?>
    </body>
</html>