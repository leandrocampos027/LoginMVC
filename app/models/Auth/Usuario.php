<?php
namespace App\Models\Auth;

use App\Core\Database;
use PDO;

class Usuario {
    private $pdo;
    private $table = 'users';

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function buscarPorEmail($email) {
        $sql = "SELECT * FROM $this->table WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($email, $password) {
        $sql = "INSERT INTO $this->table (email, password) VALUES (:email, :password)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashed);
        return $stmt->execute();
    }

    public function exists($email) {
        $sql = "SELECT id FROM $this->table WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}

class PasswordReset {
    private $pdo;
    private $table = 'password_resets';

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function generate($email) {
        $token = bin2hex(random_bytes(16));
        $expires_at = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        $stmt = $this->pdo->prepare("INSERT INTO $this->table (email, token, expires_at) VALUES (:email, :token, :expires_at)");
        $stmt->execute([
            ':email' => $email,
            ':token' => $token,
            ':expires_at' => $expires_at
        ]);

        return $token;
    }

    public function createToken($email, $token, $code) {
        $sql = "DELETE FROM $this->table WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $sql = "INSERT INTO $this->table (email, token, code, expires_at)
                VALUES (:email, :token, :code, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':code', $code);
        return $stmt->execute();
    }

    public function validateToken($token) {
        $sql = "SELECT * FROM $this->table WHERE token = :token AND expires_at >= NOW() LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($email, $newPassword) {
        $sql = "UPDATE users SET password = :password WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashed);
        return $stmt->execute();
    }

    public function clearExpiredTokens() {
        $sql = "DELETE FROM $this->table WHERE expires_at < NOW()";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute();
    }

    public function getEmailByToken($token) {
        $sql = "SELECT email FROM $this->table WHERE token = :token AND expires_at >= NOW() LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['email'] : null;
    }

    public function deleteToken($token) {
        $sql = "DELETE FROM $this->table WHERE token = :token";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':token', $token);
        return $stmt->execute();
    }
}
