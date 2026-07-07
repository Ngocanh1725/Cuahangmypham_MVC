<?php
class StoreModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllStores() {
        $sql = "SELECT * FROM stores ORDER BY id ASC";
        $result = $this->conn->query($sql);
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) $data[] = $row;
        }
        return $data;
    }

    public function getActiveStores() {
        $sql = "SELECT * FROM stores WHERE is_active = 1 ORDER BY city ASC, name ASC";
        $result = $this->conn->query($sql);
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) $data[] = $row;
        }
        return $data;
    }

    public function getStoreById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM stores WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function addStore($name, $address, $phone, $city, $open_hours, $is_active) {
        $stmt = $this->conn->prepare("INSERT INTO stores (name, address, phone, city, open_hours, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $name, $address, $phone, $city, $open_hours, $is_active);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateStore($id, $name, $address, $phone, $city, $open_hours, $is_active) {
        $stmt = $this->conn->prepare("UPDATE stores SET name=?, address=?, phone=?, city=?, open_hours=?, is_active=? WHERE id=?");
        $stmt->bind_param("sssssii", $name, $address, $phone, $city, $open_hours, $is_active, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteStore($id) {
        $stmt = $this->conn->prepare("DELETE FROM stores WHERE id=?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>
