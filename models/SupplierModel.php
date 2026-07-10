<?php
class SupplierModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllSuppliers() {
        $result = $this->conn->query("SELECT * FROM suppliers ORDER BY id DESC");
        $suppliers = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $suppliers[] = $row;
            }
        }
        return $suppliers;
    }

    public function getSupplierById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM suppliers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addSupplier($name, $phone, $email, $address) {
        $stmt = $this->conn->prepare("INSERT INTO suppliers (name, phone, email, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $email, $address);
        return $stmt->execute();
    }

    public function updateSupplier($id, $name, $phone, $email, $address) {
        $stmt = $this->conn->prepare("UPDATE suppliers SET name=?, phone=?, email=?, address=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $phone, $email, $address, $id);
        return $stmt->execute();
    }

    public function deleteSupplier($id) {
        $stmt = $this->conn->prepare("DELETE FROM suppliers WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
