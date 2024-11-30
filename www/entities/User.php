<?php
class User {
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private string $created_at;
    private PDO $db;

    public function __construct(PDO $db, int $id)
    {
        $query = $db->prepare("SELECT * FROM users WHERE id = :id");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new InvalidArgumentException("Pas d'utilisateur avec cet ID");
        }

        $this->id = $user['id'];
        $this->username = $user['username'];
        $this->email = $user['email'];
        $this->password = $user['password'];
        $this->created_at = $user['created_at'];
        $this->db = $db;
    }

    public static function create(PDO $db, string $username, string $email, string $password): User
    {
        $query = $db->prepare("INSERT INTO users (username, email, password, created_at) VALUES (:username, :email, :password, NOW())");
        $query->bindValue(':username', $username);
        $query->bindValue(':email', $email);
        $query->bindValue(':password', password_hash($password, PASSWORD_BCRYPT)); // Sécurisation du mot de passe
        $query->execute();

        return new User($db, $db->lastInsertId());
    }

    public function delete(): void
    {
        $query = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $query->bindValue(":id", $this->id, PDO::PARAM_INT);
        $query->execute();
    }

    public function update(): void
    {
        $query = $this->db->prepare("UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id");
        $query->bindValue(":username", $this->username);
        $query->bindValue(":email", $this->email);
        $query->bindValue(":password", $this->password); // Assurez-vous que le mot de passe est déjà hashé si modifié
        $query->bindValue(":id", $this->id, PDO::PARAM_INT);
        $query->execute();
    }

    // Getters et setters
    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
}
?>