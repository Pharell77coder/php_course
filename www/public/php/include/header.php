<header class="header" id="header">
    <h1>Page PHP</h1>
    <a href="home.php">Home</a>
    <a href="user.php">Utilisateur</a>
    <a href="cars.php">Voitures</a>
    <a href="cars_user.php">Propriétaires de voitures</a>
    <p><?= htmlspecialchars($username); ?></p>
    <a href="./../../entities/logout.php">Déconnexion</a>
</header>