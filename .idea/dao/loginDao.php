<?php

namespace dao;


class loginDAO {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // Fetch user by email
    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT *, COUNT(*) AS numrows FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    // Verify the user's password
    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
}
?>
