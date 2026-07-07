<?php
class TierModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllTiers() {
        $sql = "SELECT * FROM membership_tiers ORDER BY min_points ASC";
        $result = $this->conn->query($sql);
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) $data[] = $row;
        }
        return $data;
    }

    public function getTierById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM membership_tiers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function addTier($name, $discount_percent, $min_points, $description, $icon_class) {
        $stmt = $this->conn->prepare("INSERT INTO membership_tiers (name, discount_percent, min_points, description, icon_class) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdiss", $name, $discount_percent, $min_points, $description, $icon_class);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateTier($id, $name, $discount_percent, $min_points, $description, $icon_class) {
        $stmt = $this->conn->prepare("UPDATE membership_tiers SET name=?, discount_percent=?, min_points=?, description=?, icon_class=? WHERE id=?");
        $stmt->bind_param("sdissi", $name, $discount_percent, $min_points, $description, $icon_class, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteTier($id) {
        $stmt = $this->conn->prepare("DELETE FROM membership_tiers WHERE id=?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>
