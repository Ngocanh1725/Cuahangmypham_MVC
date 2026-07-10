<?php
//Chuyên trách việc lưu đơn hàng mới, lưu chi tiết đơn hàng (order_details) 
//và tra cứu lịch sử mua hàng của khách.
class OrderModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Tạo đơn hàng mới — Hỗ trợ đầy đủ e-commerce fields theo Migration V3
     * 
     * @param array $data Mảng chứa tất cả thông tin đơn hàng:
     *   - user_id, customer_name, customer_phone, customer_email
     *   - customer_address, customer_city, customer_district, customer_ward
     *   - subtotal, discount_amount, coupon_discount, member_discount
     *   - coupon_id, coupon_code, points_used, points_earned
     *   - shipping_fee, vat_amount, total_price
     *   - payment_method, delivery_method, store_id, note
     * @return int|false  Insert ID hoặc false nếu lỗi
     */
    public function createOrder($data) {
        $sql = "INSERT INTO orders (
                    user_id, customer_name, customer_phone, customer_email,
                    customer_address, customer_city, customer_district, customer_ward,
                    subtotal, discount_amount, coupon_discount, member_discount,
                    coupon_id, coupon_code, points_used, points_earned,
                    shipping_fee, vat_amount, total_price,
                    payment_method, delivery_method, store_id, note
                ) VALUES (
                    ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?,
                    ?, ?, ?, ?
                )";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        // Chuẩn bị giá trị (nullable)
        $user_id      = !empty($data['user_id']) ? intval($data['user_id']) : null;
        $name         = $data['customer_name'] ?? '';
        $phone        = $data['customer_phone'] ?? '';
        $email        = $data['customer_email'] ?? null;
        $address      = $data['customer_address'] ?? '';
        $city         = $data['customer_city'] ?? null;
        $district     = $data['customer_district'] ?? null;
        $ward         = $data['customer_ward'] ?? null;
        $subtotal     = floatval($data['subtotal'] ?? 0);
        $discount_amt = floatval($data['discount_amount'] ?? 0);
        $coupon_disc  = floatval($data['coupon_discount'] ?? 0);
        $member_disc  = floatval($data['member_discount'] ?? 0);
        $coupon_id    = !empty($data['coupon_id']) ? intval($data['coupon_id']) : null;
        $coupon_code  = $data['coupon_code'] ?? null;
        $points_used  = intval($data['points_used'] ?? 0);
        $points_earned = intval($data['points_earned'] ?? 0);
        $shipping_fee = floatval($data['shipping_fee'] ?? 0);
        $vat_amount   = floatval($data['vat_amount'] ?? 0);
        $total_price  = floatval($data['total_price'] ?? 0);
        $payment      = $data['payment_method'] ?? 'COD';
        $delivery     = $data['delivery_method'] ?? 'shipping';
        $store_id     = !empty($data['store_id']) ? intval($data['store_id']) : null;
        $note         = $data['note'] ?? null;

        $stmt->bind_param(
            "isssssssddddisissddssss",
            $user_id, $name, $phone, $email,
            $address, $city, $district, $ward,
            $subtotal, $discount_amt, $coupon_disc, $member_disc,
            $coupon_id, $coupon_code, $points_used, $points_earned,
            $shipping_fee, $vat_amount, $total_price,
            $payment, $delivery, $store_id, $note
        );

        if ($stmt->execute()) {
            $insert_id = $stmt->insert_id;
            $stmt->close();
            return $insert_id;
        }
        $stmt->close();
        return false;
    }

    /**
     * Lưu thông tin hóa đơn VAT vào bảng order_invoices
     * + Cập nhật flag vat_requested trên bảng orders
     */
    public function saveVatInvoice($order_id, $company_name, $tax_code, $company_address) {
        // Insert vào bảng order_invoices
        $stmt = $this->conn->prepare(
            "INSERT INTO order_invoices (order_id, company_name, tax_code, company_address) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("isss", $order_id, $company_name, $tax_code, $company_address);
        $stmt->execute();
        $stmt->close();

        // Cập nhật flag + thông tin VAT trên bảng orders
        $stmt2 = $this->conn->prepare(
            "UPDATE orders SET vat_requested = 1, vat_company_name = ?, vat_tax_code = ?, vat_company_address = ? WHERE id = ?"
        );
        $stmt2->bind_param("sssi", $company_name, $tax_code, $company_address, $order_id);
        $stmt2->execute();
        $stmt2->close();
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

    // Lấy thông tin hóa đơn VAT của đơn hàng
    public function getVatInvoice($order_id) {
        $stmt = $this->conn->prepare("SELECT * FROM order_invoices WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $invoice = $result->fetch_assoc();
        $stmt->close();
        return $invoice;
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

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($order_id, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Lấy tất cả đơn hàng (cho Admin)
    public function getAllOrders($limit = 50, $offset = 0) {
        $stmt = $this->conn->prepare("SELECT * FROM orders ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        $stmt->close();
        return $orders;
    }
}
?>