<?php
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

    <form id="form_user" method="POST" action=".\entities\process.php"><!-- envoyer les données au fichier process.php -->
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
