<?php

namespace tests;

use PHPUnit\Framework\TestCase;

class UserDAOTest extends TestCase
{
    private $pdo;
    private $userDAO;

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
            reset_code VARCHAR(255) NULL,
            status BOOLEAN NOT NULL
        )");

        // Insert mock users
        $password = password_hash('userpassword', PASSWORD_DEFAULT);
        $this->pdo->exec("INSERT INTO users (email, password, status) VALUES ('user@example.com', '$password', 1)");

        $this->userDAO = new UserDAO($this->pdo);
    }

    public function testCreateUser()
    {
        $result = $this->userDAO->createUser('newuser@example.com', 'newpassword');
        $this->assertTrue($result);

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = 'newuser@example.com'");
        $stmt->execute();
        $user = $stmt->fetch();

        $this->assertEquals('newuser@example.com', $user['email']);
    }

    public function testGetUserByEmail()
    {
        $user = $this->userDAO->getUserByEmail('user@example.com');
        $this->assertEquals('user@example.com', $user['email']);
        $this->assertTrue($user['status']);
    }

    public function testUpdateResetCode()
    {
        $result = $this->userDAO->updateResetCode('user@example.com', 'resetcode123');
        $this->assertTrue($result);

        $stmt = $this->pdo->prepare("SELECT reset_code FROM users WHERE email = 'user@example.com'");
        $stmt->execute();
        $user = $stmt->fetch();

        $this->assertEquals('resetcode123', $user['reset_code']);
    }

    protected function tearDown(): void
    {
        // Close the connection
        $this->pdo = null;
    }
}

