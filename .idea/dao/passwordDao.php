<?php

namespace dao;

class passwordDao {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // Verify the reset code and user ID
    public function verifyResetCode($code, $userId) {
        $stmt = $this->conn->prepare("SELECT *, COUNT(*) AS numrows FROM users WHERE reset_code = :code AND id = :id");
        $stmt->execute(['code' => $code, 'id' => $userId]);
        return $stmt->fetch();
    }

    // Reset the user's password
    public function resetPassword($userId, $newPassword) {
        try {
            $password = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->execute(['password' => $password, 'id' => $userId]);
            return ['success' => true, 'message' => 'Password successfully reset'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
?>
