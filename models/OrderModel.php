<?php
//Chuyên trách việc lưu đơn hàng mới, lưu chi tiết đơn hàng (order_details) 
//và tra cứu lịch sử mua hàng của khách.
class OrderModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tạo đơn hàng mới (Hỗ trợ đầy đủ e-commerce fields)
    public function createOrder($user_id, $name, $phone, $address, $total_price, $payment_method = 'COD', $delivery_method = 'shipping', $shipping_fee = 0, $vat_amount = 0, $discount_amount = 0, $coupon_id = null, $points_used = 0, $points_earned = 0) {
        $user_id_val = $user_id ? intval($user_id) : null;
        
        $sql = "INSERT INTO orders (user_id, customer_name, customer_phone, customer_address, total_price, payment_method, delivery_method, shipping_fee, vat_amount, discount_amount, coupon_id, points_used, points_earned) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isssdssddddii", $user_id_val, $name, $phone, $address, $total_price, $payment_method, $delivery_method, $shipping_fee, $vat_amount, $discount_amount, $coupon_id, $points_used, $points_earned);
        
        if ($stmt->execute()) {
            $insert_id = $stmt->insert_id;
            $stmt->close();
            return $insert_id;
        }
        $stmt->close();
        return false;
    }

    // Lưu chi tiết từng sản phẩm
    public function createOrderDetail($order_id, $product_id, $price, $quantity) {
        $stmt = $this->conn->prepare("INSERT INTO order_details (order_id, product_id, price, quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iidi", $order_id, $product_id, $price, $quantity);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Trừ số lượng tồn kho
    public function decreaseStock($product_id, $qty) {
        $stmt = $this->conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->bind_param("ii", $qty, $product_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Lấy thông tin chung của 1 đơn hàng
    public function getOrderById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();
        return $order;
    }

    // Lấy chi tiết các món hàng trong 1 đơn
    public function getOrderDetails($order_id) {
        $stmt = $this->conn->prepare("SELECT od.*, p.name, p.image FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $details = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $details[] = $row;
            }
        }
        $stmt->close();
        return $details;
    }

    // Lấy danh sách đơn hàng của một khách hàng
    public function getOrdersByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $orders = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        $stmt->close();
        return $orders;
    }
}
?>