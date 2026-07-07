<?php
class CouponModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllCoupons() {
        $sql = "SELECT * FROM coupons ORDER BY id DESC";
        $result = $this->conn->query($sql);
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) $data[] = $row;
        }
        return $data;
    }

    public function getCouponById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM coupons WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function addCoupon($code, $type, $discount_value, $min_order_value, $max_discount, $usage_limit, $start_date, $end_date, $is_active, $description) {
        $stmt = $this->conn->prepare("INSERT INTO coupons (code, type, discount_value, min_order_value, max_discount, usage_limit, start_date, end_date, is_active, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdddissis", $code, $type, $discount_value, $min_order_value, $max_discount, $usage_limit, $start_date, $end_date, $is_active, $description);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateCoupon($id, $code, $type, $discount_value, $min_order_value, $max_discount, $usage_limit, $start_date, $end_date, $is_active, $description) {
        $stmt = $this->conn->prepare("UPDATE coupons SET code=?, type=?, discount_value=?, min_order_value=?, max_discount=?, usage_limit=?, start_date=?, end_date=?, is_active=?, description=? WHERE id=?");
        $stmt->bind_param("ssdddissisi", $code, $type, $discount_value, $min_order_value, $max_discount, $usage_limit, $start_date, $end_date, $is_active, $description, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteCoupon($id) {
        $stmt = $this->conn->prepare("DELETE FROM coupons WHERE id=?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Validate coupon (dùng cho checkout AJAX)
    public function validateCoupon($code) {
        $stmt = $this->conn->prepare("SELECT * FROM coupons WHERE code = ? AND is_active = 1");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $coupon = $result->fetch_assoc();
        $stmt->close();

        if (!$coupon) return ['valid' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã tắt.'];

        $now = date('Y-m-d H:i:s');
        if ($coupon['start_date'] && $now < $coupon['start_date']) return ['valid' => false, 'message' => 'Mã giảm giá chưa đến ngày áp dụng.'];
        if ($coupon['end_date'] && $now > $coupon['end_date']) return ['valid' => false, 'message' => 'Mã giảm giá đã hết hạn.'];
        if ($coupon['usage_limit'] !== null && $coupon['used_count'] >= $coupon['usage_limit']) return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.'];

        return ['valid' => true, 'coupon' => $coupon];
    }
}
?>
