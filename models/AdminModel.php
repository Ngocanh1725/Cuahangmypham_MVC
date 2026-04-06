<?php
class AdminModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- CÁC HÀM DÀNH CHO DASHBOARD ---
    public function getTotalProducts() {
        $sql = "SELECT COUNT(*) as total FROM products";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function getNewOrdersCount() {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE status = 'Chờ xử lý'";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function getTotalRevenue() {
        $sql = "SELECT SUM(total_price) as total FROM orders WHERE status = 'Hoàn thành'";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ? $row['total'] : 0;
    }

    // --- HÀM MỚI: LẤY DANH SÁCH SẢN PHẨM ---
    public function getAllProducts() {
        $sql = "SELECT * FROM products ORDER BY id DESC";
        $result = $this->conn->query($sql);
        
        $products = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        return $products;
    }

    // --- HÀM MỚI: THÊM SẢN PHẨM ---
    public function addProduct($name, $price, $category, $status, $image) {
        $name = $this->conn->real_escape_string($name);
        $category = $this->conn->real_escape_string($category);
        $sql = "INSERT INTO products (name, price, category, status, image) VALUES ('$name', '$price', '$category', '$status', '$image')";
        return $this->conn->query($sql);
    }

    // --- HÀM MỚI: LẤY 1 SẢN PHẨM THEO ID (Dùng để hiển thị lên form sửa) ---
    public function getProductById($id) {
        $id = intval($id);
        $sql = "SELECT * FROM products WHERE id = $id";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    // --- HÀM MỚI: CẬP NHẬT SẢN PHẨM ---
    public function updateProduct($id, $name, $price, $category, $status, $image) {
        $id = intval($id);
        $name = $this->conn->real_escape_string($name);
        $category = $this->conn->real_escape_string($category);
        $sql = "UPDATE products SET name='$name', price='$price', category='$category', status='$status', image='$image' WHERE id=$id";
        return $this->conn->query($sql);
    }

    // --- HÀM MỚI: XÓA SẢN PHẨM ---
    public function deleteProduct($id) {
        $id = intval($id);
        $sql = "DELETE FROM products WHERE id=$id";
        return $this->conn->query($sql);
    }

    // --- CÁC HÀM MỚI: QUẢN LÝ ĐƠN HÀNG ---
    
    // Lấy toàn bộ danh sách đơn hàng
    public function getAllOrders() {
        $sql = "SELECT * FROM orders ORDER BY id DESC";
        $result = $this->conn->query($sql);
        $orders = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        return $orders;
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($id, $status) {
        $id = intval($id);
        $status = $this->conn->real_escape_string($status);
        $sql = "UPDATE orders SET status='$status' WHERE id=$id";
        return $this->conn->query($sql);
    }

    // --- HÀM MỚI: LẤY THÔNG TIN 1 ĐƠN HÀNG ---
    public function getOrderById($id) {
        $id = intval($id);
        $sql = "SELECT * FROM orders WHERE id = $id";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    // --- HÀM MỚI: LẤY CHI TIẾT SẢN PHẨM TRONG ĐƠN ---
    public function getOrderDetails($order_id) {
        // ... (code cũ)
    }

    // --- CÁC HÀM MỚI: QUẢN LÝ TÀI KHOẢN ---

    // Lấy toàn bộ danh sách người dùng
    public function getAllUsers() {
        $sql = "SELECT * FROM users ORDER BY role ASC, id DESC";
        $result = $this->conn->query($sql);
        $users = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }

    // Lấy 1 người dùng theo ID
    public function getUserById($id) {
        $id = intval($id);
        $sql = "SELECT * FROM users WHERE id = $id";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    // Kiểm tra Email đã tồn tại chưa (tránh trùng lặp khi thêm)
    public function checkEmailExists($email, $exclude_id = 0) {
        $email = $this->conn->real_escape_string($email);
        $sql = "SELECT id FROM users WHERE email = '$email' AND id != $exclude_id";
        $result = $this->conn->query($sql);
        return ($result && $result->num_rows > 0);
    }

    // Thêm người dùng mới
    public function addUser($fullname, $email, $password, $role) {
        $fullname = $this->conn->real_escape_string($fullname);
        $email = $this->conn->real_escape_string($email);
        $sql = "INSERT INTO users (full_name, email, password, role) VALUES ('$fullname', '$email', '$password', '$role')";
        return $this->conn->query($sql);
    }

    // Cập nhật người dùng (Có thể đổi hoặc giữ nguyên mật khẩu)
    public function updateUser($id, $fullname, $email, $role, $password = null) {
        $id = intval($id);
        $fullname = $this->conn->real_escape_string($fullname);
        $email = $this->conn->real_escape_string($email);
        
        if ($password != null) {
            $sql = "UPDATE users SET full_name='$fullname', email='$email', role='$role', password='$password' WHERE id=$id";
        } else {
            $sql = "UPDATE users SET full_name='$fullname', email='$email', role='$role' WHERE id=$id";
        }
        return $this->conn->query($sql);
    }

    // Xóa người dùng
    public function deleteUser($id) {
        $id = intval($id);
        $sql = "DELETE FROM users WHERE id=$id";
        return $this->conn->query($sql);
    }
}
?>