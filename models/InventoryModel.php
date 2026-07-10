<?php
class InventoryModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getLogs($limit = 50) {
        $sql = "SELECT i.*, p.name as product_name, s.name as supplier_name 
                FROM inventory_logs i
                JOIN products p ON i.product_id = p.id
                LEFT JOIN suppliers s ON i.supplier_id = s.id
                ORDER BY i.id DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $logs = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $logs[] = $row;
            }
        }
        return $logs;
    }

    // Admin Nhập hàng (Cộng stock)
    public function addStock($product_id, $supplier_id, $amount, $reason = 'Nhập hàng mới') {
        if ($amount <= 0) return false;
        
        $this->conn->begin_transaction();
        try {
            // Cộng stock vào products
            $stmt1 = $this->conn->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            $stmt1->bind_param("ii", $amount, $product_id);
            $stmt1->execute();

            // Ghi log
            $stmt2 = $this->conn->prepare("INSERT INTO inventory_logs (product_id, supplier_id, change_amount, reason) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("iiis", $product_id, $supplier_id, $amount, $reason);
            $stmt2->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // Xuất kho (Trừ stock) khi có đơn hàng
    public function deductStock($product_id, $amount, $reason) {
        if ($amount <= 0) return false;

        $this->conn->begin_transaction();
        try {
            // Check current stock first to prevent negative stock (optional, but good practice)
            $stmtCheck = $this->conn->prepare("SELECT stock FROM products WHERE id = ?");
            $stmtCheck->bind_param("i", $product_id);
            $stmtCheck->execute();
            $res = $stmtCheck->get_result();
            if ($row = $res->fetch_assoc()) {
                if ($row['stock'] < $amount) {
                    throw new Exception("Không đủ tồn kho");
                }
            }

            // Trừ stock
            $stmt1 = $this->conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $stmt1->bind_param("ii", $amount, $product_id);
            $stmt1->execute();

            // Ghi log (change_amount âm)
            $change = -$amount;
            $supplier_null = null;
            $stmt2 = $this->conn->prepare("INSERT INTO inventory_logs (product_id, supplier_id, change_amount, reason) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("iiis", $product_id, $supplier_null, $change, $reason);
            $stmt2->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // Hoàn kho (Cộng lại stock) khi hủy đơn
    public function restock($product_id, $amount, $reason) {
        if ($amount <= 0) return false;

        $this->conn->begin_transaction();
        try {
            // Cộng stock
            $stmt1 = $this->conn->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            $stmt1->bind_param("ii", $amount, $product_id);
            $stmt1->execute();

            // Ghi log (change_amount dương)
            $supplier_null = null;
            $stmt2 = $this->conn->prepare("INSERT INTO inventory_logs (product_id, supplier_id, change_amount, reason) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("iiis", $product_id, $supplier_null, $amount, $reason);
            $stmt2->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // Lấy tồn kho tại một cơ sở
    public function getStoreStock($store_id, $product_id) {
        $stmt = $this->conn->prepare("SELECT stock FROM store_inventory WHERE store_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $store_id, $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            return $row['stock'];
        }
        return 0; // Mặc định 0 nếu không có bản ghi
    }

    // Trừ kho tại một cơ sở cụ thể
    public function decreaseStoreStock($store_id, $product_id, $amount, $reason) {
        if ($amount <= 0) return false;

        $this->conn->begin_transaction();
        try {
            $stmtCheck = $this->conn->prepare("SELECT stock FROM store_inventory WHERE store_id = ? AND product_id = ?");
            $stmtCheck->bind_param("ii", $store_id, $product_id);
            $stmtCheck->execute();
            $res = $stmtCheck->get_result();
            if ($row = $res->fetch_assoc()) {
                if ($row['stock'] < $amount) {
                    throw new Exception("Không đủ tồn kho tại cơ sở này");
                }
            } else {
                throw new Exception("Sản phẩm không tồn tại ở cơ sở này");
            }

            // Trừ stock cơ sở
            $stmt1 = $this->conn->prepare("UPDATE store_inventory SET stock = stock - ? WHERE store_id = ? AND product_id = ?");
            $stmt1->bind_param("iii", $amount, $store_id, $product_id);
            $stmt1->execute();

            // Ghi log vào inventory_logs với chú thích rõ là trừ ở cơ sở nào
            $change = -$amount;
            $supplier_null = null;
            $fullReason = "[Cơ sở $store_id] " . $reason;
            $stmt2 = $this->conn->prepare("INSERT INTO inventory_logs (product_id, supplier_id, change_amount, reason) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("iiis", $product_id, $supplier_null, $change, $fullReason);
            $stmt2->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
?>
