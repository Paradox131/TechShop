<?php

namespace tests;

use PHPUnit\Framework\TestCase;


class PasswordDAOTest extends TestCase
{
    private $pdo;
    private $passwordDAO;

    protected function setUp(): void
    {
        // Mock a PDO connection
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create users table
        $this->pdo->exec("CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            reset_code VARCHAR(255) NOT NULL
        )");

        // Insert mock user with a reset code
        $password = password_hash('oldpassword', PASSWORD_DEFAULT);
        $this->pdo->exec("INSERT INTO users (email, password, reset_code) VALUES ('reset@example.com', '$password', 'reset123')");

        $this->passwordDAO = new PasswordDAO($this->pdo);
    }

    public function testResetPassword()
    {
        $newPassword = password_hash('newpassword', PASSWORD_DEFAULT);
        $result = $this->passwordDAO->resetPassword('reset123', 1, $newPassword);
        $this->assertTrue($result);

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = 1");
        $stmt->execute();
        $user = $stmt->fetch();

        $this->assertTrue(password_verify('newpassword', $user['password']));
    }

    public function testVerifyResetCode()
    {
        $user = $this->passwordDAO->verifyResetCode('reset123', 1);
        $this->assertEquals('reset@example.com', $user['email']);
    }

    protected function tearDown(): void
    {
        // Close the connection
        $this->pdo = null;
    }
}

