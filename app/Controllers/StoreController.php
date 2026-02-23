<?php
class StoreController {

    private $conn;

    public function __construct() {
       
        $this->conn = new mysqli("localhost", "root", "", "school_db");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

    
    }

   
    public function getAllItems() {
        $result = $this->conn->query("SELECT * FROM store");
        $items = [];
        while($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }

  
    public function addItem($name, $price, $quantity) {
        $stmt = $this->conn->prepare("INSERT INTO store (name, price, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $name, $price, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteItem($id) {
        $stmt = $this->conn->prepare("DELETE FROM store WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }


    public function updateItem($id, $name, $price, $quantity) {
        $stmt = $this->conn->prepare("UPDATE store SET name=?, price=?, quantity=? WHERE id=?");
        $stmt->bind_param("sdii", $name, $price, $quantity, $id);
        $stmt->execute();
        $stmt->close();
    }
}