<?php

namespace tests;

use PHPUnit\Framework\TestCase;

class cartDAOTest extends TestCase
{
    private $pdo;
    private $cartDAO;

    protected function setUp(): void
    {
        // Mock a PDO connection
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create cart and products table
        $this->pdo->exec("CREATE TABLE cart (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            product_id INTEGER,
            quantity INTEGER
        )");

        $this->cartDAO = new CartDAO($this->pdo);
    }

    public function testAddToCart()
    {
        $result = $this->cartDAO->addToCart(1, 101, 2);
        $this->assertTrue($result);

        $stmt = $this->pdo->prepare("SELECT * FROM cart WHERE user_id = 1 AND product_id = 101");
        $stmt->execute();
        $cartItem = $stmt->fetch();

        $this->assertEquals(2, $cartItem['quantity']);
    }

    public function testUpdateCartItemQuantity()
    {
        // Insert a mock cart item
        $this->pdo->exec("INSERT INTO cart (user_id, product_id, quantity) VALUES (1, 101, 2)");

        $result = $this->cartDAO->updateCartItemQuantity(1, 3);
        $this->assertTrue($result);

        $stmt = $this->pdo->prepare("SELECT * FROM cart WHERE id = 1");
        $stmt->execute();
        $cartItem = $stmt->fetch();

        $this->assertEquals(3, $cartItem['quantity']);
    }

    public function testRemoveFromCart()
    {
        // Insert a mock cart item
        $this->pdo->exec("INSERT INTO cart (user_id, product_id, quantity) VALUES (1, 101, 2)");

        $result = $this->cartDAO->removeFromCart(1);
        $this->assertTrue($result);

        $stmt = $this->pdo->prepare("SELECT * FROM cart WHERE id = 1");
        $stmt->execute();
        $cartItem = $stmt->fetch();

        $this->assertFalse($cartItem); // Should return false since the item was removed
    }

    protected function tearDown(): void
    {
        // Close the connection
        $this->pdo = null;
    }
}

