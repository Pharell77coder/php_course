<?php
    session_start();

    // Vérifie si l'utilisateur est connecté
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        $username = "Invité";
    }

    require_once './../../entities/database.php';

    $db = connect();

    // Assigner un utilisateur à un véhicule
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['assign'])) {
            $userId = (int)$_POST['user_id'];
            $carId = (int)$_POST['car_id'];

            $assignQuery = $db->prepare("INSERT INTO cars_users (id_user, id_car, assigned_at) VALUES (:id_user, :id_car, NOW())");
            $assignQuery->execute(['id_user' => $userId, 'id_car' => $carId]);

            // Recharge la page après assignation
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        // Modifier une association utilisateur-voiture
        if (isset($_POST['update'])) {
            $oldUserId = (int)$_POST['old_id_user'];
            $oldCarId = (int)$_POST['old_id_car'];
            $newUserId = (int)$_POST['new_user_id'];
            $newCarId = (int)$_POST['new_car_id'];

            $updateQuery = $db->prepare("
                UPDATE cars_users 
                SET id_user = :new_user_id, id_car = :new_car_id 
                WHERE id_user = :old_user_id AND id_car = :old_car_id
            ");
            $updateQuery->execute([
                'new_user_id' => $newUserId,
                'new_car_id' => $newCarId,
                'old_user_id' => $oldUserId,
                'old_car_id' => $oldCarId
            ]);

            // Recharge la page après modification
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        // Supprimer une association utilisateur-voiture
        if (isset($_POST['delete'])) {
            $userId = (int)$_POST['id_user'];
            $carId = (int)$_POST['id_car'];

            $deleteQuery = $db->prepare("DELETE FROM cars_users WHERE id_user = :id_user AND id_car = :id_car");
            $deleteQuery->execute(['id_user' => $userId, 'id_car' => $carId]);

            // Recharge la page après suppression
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    // Récupérer les utilisateurs
    $usersQuery = $db->query("SELECT id, username FROM users");
    $users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les véhicules
    $carsQuery = $db->query("SELECT id, model FROM cars");
    $cars = $carsQuery->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les associations utilisateurs-voitures
    $carsUserQuery = $db->query("
        SELECT cu.id_user, cu.id_car, cu.assigned_at, u.username, c.model 
        FROM cars_users cu
        JOIN users u ON cu.id_user = u.id
        JOIN cars c ON cu.id_car = c.id
    ");
    $carsUsers = $carsUserQuery->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestion des associations utilisateurs-voitures</title>
        <link rel="stylesheet" href=".\..\css\header.css">
        <link rel="stylesheet" href=".\..\css\global.css">
    </head>
    <body>
        <?php include './include/header.php'; ?>

        <h1>Assigner un utilisateur à un véhicule</h1>
        <form method="POST">
            <label for="user_id">Utilisateur :</label>
            <select id="user_id" name="user_id" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?= htmlspecialchars($user['id']) ?>">
                        <?= htmlspecialchars($user['username']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="car_id">Véhicule :</label>
            <select id="car_id" name="car_id" required>
                <?php foreach ($cars as $car): ?>
                    <option value="<?= htmlspecialchars($car['id']) ?>">
                        <?= htmlspecialchars($car['model']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="assign">Assigner</button>
        </form>

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
