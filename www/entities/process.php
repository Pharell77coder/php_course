<?php
session_start(); 

require_once './database.php';

$db = connect();

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert-user'])) {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hachage sécurisé du mot de passe


    try {
        // Vérification si l'utilisateur existe déjà
        $checkQuery = $db->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $checkQuery->execute([
            ':username' => $username,
            ':email' => $email
        ]);

        if ($checkQuery->rowCount() > 0) {
            header('Location: ./../index.php?error=Erreur : Cet utilisateur ou email existe déjà.');
            exit();
        } else {
            // Insérer l'utilisateur dans la base de données
            $query = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $query->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $password,
            ]);}

            // Stocker le pseudo dans la session
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

            // Rediriger vers une nouvelle page après l'inscription
            header('Location: ../../public/php/home.php');
            exit();
        
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
