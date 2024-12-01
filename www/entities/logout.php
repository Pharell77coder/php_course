<?php
session_start(); // Démarre la session
session_destroy(); // Détruit toutes les données de la session
header('Location: ./../index.php'); // Redirige vers le formulaire
exit();
?>
