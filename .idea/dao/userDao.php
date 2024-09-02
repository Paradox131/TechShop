<?php

namespace dao;



class userDao {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // Check if email already exists
    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS numrows FROM users WHERE email=:email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row['numrows'] > 0;
    }

    // Add a new user
    public function addUser($email, $password, $firstname, $lastname, $code, $created_on) {
        $stmt = $this->conn->prepare("INSERT INTO users (email, password, firstname, lastname, activate_code, created_on) 
                                      VALUES (:email, :password, :firstname, :lastname, :code, :created_on)");
        $stmt->execute([
            'email' => $email,
            'password' => $password,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'code' => $code,
            'created_on' => $created_on
        ]);
        return $this->conn->lastInsertId();
    }
}
?>
