<?php

namespace tests;

use PHPUnit\Framework\TestCase;


class loginDAOTest extends TestCase
{
    private $pdo;
    private $loginDAO;

    protected function setUp(): void
    {
        // Mock a PDO connection (or use a real connection if available)
        $this->pdo = new PDO('sqlite::memory:'); // Using an in-memory SQLite DB for testing
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create a users table for testing
        $this->pdo->exec("CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            status BOOLEAN NOT NULL,
            type BOOLEAN NOT NULL
        )");

        // Insert a mock user
        $password = password_hash('password123', PASSWORD_DEFAULT);
        $this->pdo->exec("INSERT INTO users (email, password, status, type) VALUES ('test@example.com', '$password', 1, 0)");

        $this->loginDAO = new LoginDAO($this->pdo);
    }

    public function testGetUserByEmail()
    {
        $user = $this->loginDAO->getUserByEmail('test@example.com');
        $this->assertEquals('test@example.com', $user['email']);
        $this->assertTrue($user['status']);
    }

    public function testVerifyPassword()
    {
        $user = $this->loginDAO->getUserByEmail('test@example.com');
        $isValid = $this->loginDAO->verifyPassword('password123', $user['password']);
        $this->assertTrue($isValid);
    }

    protected function tearDown(): void
    {
        // Close the connection
        $this->pdo = null;
    }
}

