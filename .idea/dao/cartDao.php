<?php

namespace dao;

class cartDAO {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // Add item to cart
    public function addItemToCart($userId, $productId, $quantity) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS numrows FROM cart WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $row = $stmt->fetch();

        if ($row['numrows'] < 1) {
            try {
                $stmt = $this->conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
                $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'quantity' => $quantity]);
                return ['error' => false, 'message' => 'Item added to cart'];
            } catch (PDOException $e) {
                return ['error' => true, 'message' => $e->getMessage()];
            }
        } else {
            return ['error' => true, 'message' => 'Product already in cart'];
        }
    }

    // Delete item from cart
    public function deleteItemFromCart($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM cart WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return ['error' => false, 'message' => 'Deleted'];
        } catch (PDOException $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    // Update item quantity in cart
    public function updateCartItemQuantity($id, $quantity) {
        try {
            $stmt = $this->conn->prepare("UPDATE cart SET quantity = :quantity WHERE id = :id");
            $stmt->execute(['quantity' => $quantity, 'id' => $id]);
            return ['error' => false, 'message' => 'Updated'];
        } catch (PDOException $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}
?>
