<?php
session_start(); 

require_once './database.php';

$db = connect();

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert-user'])) {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hachage sécurisé du mot de passe

    // Insertion de l'utilisateur dans la base de données
    $query = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

    try {
        $query->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password,
        ]);
       
        // Stocker le pseudo dans la session
        $_SESSION['username'] = $username;

        // Rediriger vers une nouvelle page après l'inscription
        header('Location: .\..\..\public\php\home.php');
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
