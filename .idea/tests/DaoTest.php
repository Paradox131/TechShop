<?php

namespace tests;

use PHPUnit\Framework\TestCase;

class DAOTest extends TestCase
{
    private $pdo;
    private $Dao;

    protected function setUp(): void
    {
        // Mock a PDO connection
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create a config table for testing
        $this->pdo->exec("CREATE TABLE config (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            value TEXT NOT NULL
        )");

        // Insert mock config settings
        $this->pdo->exec("INSERT INTO config (name, value) VALUES ('site_name', 'Test Site')");
        $this->pdo->exec("INSERT INTO config (name, value) VALUES ('maintenance_mode', '0')");

        $this->configDAO = new ConfigDAO($this->pdo);
    }

    public function testGetConfigByName()
    {
        $value = $this->DAO->getConfigByName('site_name');
        $this->assertEquals('Test Site', $value);
    }

    public function testUpdateConfigValue()
    {
        $this->DAO->updateConfigValue('maintenance_mode', '1');
        $value = $this->DAO->getConfigByName('maintenance_mode');
        $this->assertEquals('1', $value);
    }

    protected function tearDown(): void
    {
        // Close the connection
        $this->pdo = null;
    }
}

