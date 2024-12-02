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

    // Insertion dans la base de données
    if(isset($_POST['insert-car'])) {
        Car::create($db, $_POST['model'], $_POST['brand'], $_POST['price'], $_POST['build_at']);
    }
    // Suppression d'une voiture
    if (isset($_POST['delete-car'])) {
        $carId = (int)$_POST['id-car'];

        $deleteQuery = $db->prepare("DELETE FROM cars WHERE id = :id");
        $deleteQuery->execute(['id' => $carId]);

    }

    // Modification d'une voiture
    if (isset($_POST['update-car'])) {
        $carId = (int)$_POST['id-car'];
        $model = htmlspecialchars($_POST['model']);
        $brand = htmlspecialchars($_POST['brand']);
        $price = (float)$_POST['price'];
        $buildAt = htmlspecialchars($_POST['build_at']);

        $updateQuery = $db->prepare("UPDATE cars SET model = :model, brand = :brand, price = :price, build_at = :build_at WHERE id = :id");
        $updateQuery->execute([
            'model' => $model,
            'brand' => $brand,
            'price' => $price,
            'build_at' => $buildAt,
            'id' => $carId
        ]);

    }

    // Récupération de la liste des voitures
    $query = $db->prepare("SELECT id, model, brand, price, build_at FROM cars");
    $query->execute();
    $cars = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des voitures</title>
    <link rel="stylesheet" href=".\..\css\header.css">
    <link rel="stylesheet" href=".\..\css\global.css">
</head>
<body>
    <?php include './include/header.php'; ?>

    <h1>Gestion des voitures</h1>

    <form id="form_user" method="POST">
    <label for="model">Pseudo :</label>
    <input type="text" id="model" name="model" placeholder="Votre Nom d'utilisateur..">

    <label for="brand">Email :</label>
    <input type="text" id="brand" name="brand" placeholder="Votre adresse mail..">

    <label for="price">Mot de passe :</label>
    <input type="number" step="0.01" id="price" name="price" placeholder="Votre pseudo..">
    
    <label for="build_at">Date de fabrication:</label>
    <input type="date" id="build_at" name="build_at" placeholder="Votre pseudo..">
    <br>

    <input type="submit" name="insert-car" id="submit-btn" value="Envoyer">
    </form>


    <?php if ($cars): ?>
        <table>
            <tr>
                <th>Modèle</th>
                <th>Marque</th>
                <th>Prix</th>
                <th>Date de fabrication</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($cars as $car): ?>
                <tr>
                    <!-- Formulaire pour modifier une voiture -->
                    <form method="POST" style="display: inline;">
                        <td>
                            <input type="text" name="model" value="<?= htmlspecialchars($car->model) ?>" required>
                        </td>
                        <td>
                            <input type="text" name="brand" value="<?= htmlspecialchars($car->brand) ?>" required>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($car->price) ?>" required>
                        </td>
                        <td>
                            <input type="date" name="build_at" value="<?= htmlspecialchars($car->build_at) ?>" required>
                        </td>
                        <td>
                            <input type="hidden" name="id-car" value="<?= $car->id ?>">
                            <button type="submit" name="update-car" class="update-car">Modifier</button>
                        </td>
                    </form>

                    <!-- Formulaire pour supprimer une voiture -->
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id-car" value="<?= $car->id ?>">
                            <button type="submit" name="delete-car" class="delete-car">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucune voiture trouvée.</p>
    <?php endif; ?>
</body>
</html>
