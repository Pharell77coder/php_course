<?php
$error = isset($_GET['error']) ? $_GET['error'] : null;
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
    <div class="grid-container">
            <!-- Ligne 1 -->
            <div class="l1c1">
                <h1>Créer un compte</h1>
            </div>
            <div class="l1c2">
                <h1>Connexion</h1>
            </div>

            <!-- Ligne 2 -->
            <div class="l2c1">
                <form id="form_user" method="POST" action=".\entities\process.php">
                    <label for="username">Pseudo :</label>
                    <input type="text" id="username" name="username" placeholder="Nom d'utilisateur...">

                    <label for="email">Email :</label>
                    <input type="text" id="email" name="email" placeholder="Adresse mail...">

                    <label for="password">Mot de passe :</label>
                    <input type="text" id="password" name="password" placeholder="Mot de passe...">

                    <input type="submit" name="insert-user" id="submit-btn" value="S'inscrire">
                </form>
            </div>
            <div class="l2c2">
                <form method="POST" action=".\entities\login.php">
                    <label for="username">Pseudo :</label>
                    <input type="text" id="username" name="username" placeholder="Nom d'utilisateur..." required>

                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" placeholder="Mot de passe..." required>

                    <input type="submit" value="Se connecter">
                </form>
            </div>

            <!-- Ligne 3 -->
            <div class="l3c1">
                <?php if (isset($_GET['error'])): ?>
                    <p class="error-message">
                        <?= htmlspecialchars($_GET['error']); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>
