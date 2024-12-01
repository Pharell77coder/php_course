<?php
session_start();
require_once 'database.php'; // Fichier pour connecter à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    try {
        // Connexion à la base de données
        $db = connect();

        // Rechercher l'utilisateur dans la base de données
        $query = $db->prepare('SELECT * FROM users WHERE username = :username');
        $query->execute(['username' => $username]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie : enregistrer l'utilisateur en session
            $_SESSION['username'] = $user['username'];
            header('Location: ../../public/php/home.php'); // Redirection vers la page d'accueil
            exit();
        } else {
            // Erreur de connexion
            header('Location: ./../index.php?error=Pseudo ou mot de passe incorrect');
            exit();
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
