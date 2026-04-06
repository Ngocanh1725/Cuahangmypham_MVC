<?php
class OrderModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tạo đơn hàng mới (Lưu thông tin chung của khách)
    public function createOrder($user_id, $name, $phone, $address, $total_price, $payment_method = 'COD') {
        $user_id_val = $user_id ? intval($user_id) : 'NULL';
        $name = $this->conn->real_escape_string($name);
        $phone = $this->conn->real_escape_string($phone);
        $address = $this->conn->real_escape_string($address);
        $payment_method = $this->conn->real_escape_string($payment_method);
        
        $sql = "INSERT INTO orders (user_id, customer_name, customer_phone, customer_address, total_price, payment_method) 
                VALUES ($user_id_val, '$name', '$phone', '$address', $total_price, '$payment_method')";
        
        if ($this->conn->query($sql)) {
            // Trả về ID của đơn hàng vừa tạo xong
            return $this->conn->insert_id;
        }
        return false;
    }

    // Lưu chi tiết từng sản phẩm khách mua vào đơn hàng đó
    public function createOrderDetail($order_id, $product_id, $price, $quantity) {
        $sql = "INSERT INTO order_details (order_id, product_id, price, quantity) 
                VALUES ($order_id, $product_id, $price, $quantity)";
        return $this->conn->query($sql);
    }

    // Lấy thông tin chung của 1 đơn hàng để in hóa đơn
    public function getOrderById($id) {
        $id = intval($id);
        $sql = "SELECT * FROM orders WHERE id = $id";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    // Lấy chi tiết các món hàng trong 1 đơn
    public function getOrderDetails($order_id) {
        $order_id = intval($order_id);
        $sql = "SELECT od.*, p.name, p.image 
                FROM order_details od 
                JOIN products p ON od.product_id = p.id 
                WHERE od.order_id = $order_id";
        $result = $this->conn->query($sql);
        
        $details = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $details[] = $row;
            }
        }
        return $details;
    }

    // --- HÀM MỚI: LẤY DANH SÁCH ĐƠN HÀNG CỦA MỘT KHÁCH HÀNG ---
    public function getOrdersByUserId($user_id) {
        $user_id = intval($user_id);
        $sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC";
        $result = $this->conn->query($sql);
        
        $orders = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        return $orders;
    }
}
?>