<?php
class AdminModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getConn() {
        return $this->conn;
    }

    public function getTotalProducts() {
        $sql = "SELECT COUNT(*) as total FROM products";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function getLowStockProducts($threshold = 10) {
        $sql = "SELECT id, name, stock, image, category_id FROM products WHERE stock <= ? ORDER BY stock ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $threshold);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        $stmt->close();
        return $data;
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

    // HÀM MỚI: Tính doanh thu chia theo Tiền mặt (COD) và Chuyển khoản (QR)
    public function getRevenueByPaymentMethod() {
        $sql = "SELECT payment_method, SUM(total_price) as total FROM orders WHERE status = 'Hoàn thành' GROUP BY payment_method";
        $result = $this->conn->query($sql);
        $data = ['cod' => 0, 'qr' => 0];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (strpos(strtolower($row['payment_method']), 'cod') !== false || strpos(strtolower($row['payment_method']), 'nhận hàng') !== false) {
                    $data['cod'] += $row['total'];
                } else {
                    $data['qr'] += $row['total'];
                }
            }
        }
        return $data;
    }

    public function getRevenueDetails() {
        $sql = "SELECT id, customer_name, customer_phone, order_date, total_price, payment_method FROM orders WHERE status = 'Hoàn thành' ORDER BY order_date DESC";
        $result = $this->conn->query($sql);
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // ---------------------------------------------------------
    // SẢN PHẨM (PRODUCTS) & DANH MỤC (CATEGORIES)
    // ---------------------------------------------------------
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $result = $this->conn->query($sql);
        $categories = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        return $categories;
    }

    public function getAllProducts($search = '', $category_id = '', $brand_id = '') {
        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name 
                FROM products p 
                LEFT JOIN brands b ON p.brand_id = b.id 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE 1=1";
        $types = "";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (p.name LIKE ? OR p.id = ?)";
            $types .= "si";
            $params[] = "%$search%";
            $params[] = intval($search);
        }
        if (!empty($category_id)) {
            $sql .= " AND p.category_id = ?";
            $types .= "i";
            $params[] = intval($category_id);
        }
        if (!empty($brand_id)) {
            $sql .= " AND p.brand_id = ?";
            $types .= "i";
            $params[] = intval($brand_id);
        }

        $sql .= " ORDER BY p.id DESC";
        
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $bindNames[] = $types;
            for ($i = 0; $i < count($params); $i++) {
                $bindName = 'bind' . $i;
                $$bindName = $params[$i];
                $bindNames[] = &$$bindName;
            }
            call_user_func_array([$stmt, 'bind_param'], $bindNames);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        $stmt->close();
        return $products;
    }

    public function addProduct($name, $price, $category_id, $status, $image, $stock, $brand_id = null, $is_flash_sale = 0, $is_trending = 0, $is_summer = 0) {
        $stmt = $this->conn->prepare("INSERT INTO products (name, price, category_id, status, image, stock, brand_id, is_flash_sale, is_trending, is_summer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdiisiiiii", $name, $price, $category_id, $status, $image, $stock, $brand_id, $is_flash_sale, $is_trending, $is_summer);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        return $product;
    }

    public function updateProduct($id, $name, $price, $category_id, $status, $image, $stock, $brand_id = null, $is_flash_sale = 0, $is_trending = 0, $is_summer = 0) {
        $stmt = $this->conn->prepare("UPDATE products SET name=?, price=?, category_id=?, status=?, image=?, stock=?, brand_id=?, is_flash_sale=?, is_trending=?, is_summer=? WHERE id=?");
        $stmt->bind_param("sdiisiiiiii", $name, $price, $category_id, $status, $image, $stock, $brand_id, $is_flash_sale, $is_trending, $is_summer, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteProduct($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id=?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updatePromotion($id, $old_price, $is_flash_sale = 0) {
        $stmt = $this->conn->prepare("UPDATE products SET old_price = ?, is_flash_sale = ? WHERE id = ?");
        $stmt->bind_param("iii", $old_price, $is_flash_sale, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // ---------------------------------------------------------
    // QUẢN LÝ BANNER QUẢNG CÁO (Đã tách sang BannerModel.php)
    // THƯƠNG HIỆU (Đã tách sang BrandModel.php)
    // ---------------------------------------------------------

    // ---------------------------------------------------------
    // ĐƠN HÀNG & TÀI KHOẢN (ORDERS & USERS)
    // ---------------------------------------------------------
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

    public function updateOrderStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function increaseStock($product_id, $qty) {
        $stmt = $this->conn->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
        $stmt->bind_param("ii", $qty, $product_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getOrderById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();
        return $order;
    }

    // Lấy chi tiết sản phẩm trong đơn hàng
    public function getOrderDetails($orderId) {
        $stmt = $this->conn->prepare("SELECT od.*, p.name, p.image FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = ?");
        $stmt->bind_param("i", $orderId);
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

    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function checkEmailExists($email, $exclude_id = 0) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $exclude_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = ($result && $result->num_rows > 0);
        $stmt->close();
        return $exists;
    }

    public function addUser($fullname, $email, $password, $role, $permissions = null) {
        $stmt = $this->conn->prepare("INSERT INTO users (full_name, email, password, role, permissions) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $fullname, $email, $password, $role, $permissions);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateUser($id, $fullname, $email, $role, $password = null, $permissions = null) {
        if ($password != null) {
            $stmt = $this->conn->prepare("UPDATE users SET full_name=?, email=?, role=?, password=?, permissions=? WHERE id=?");
            $stmt->bind_param("ssissi", $fullname, $email, $role, $password, $permissions, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET full_name=?, email=?, role=?, permissions=? WHERE id=?");
            $stmt->bind_param("ssisi", $fullname, $email, $role, $permissions, $id);
        }
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // ---------------------------------------------------------
    // CẤU HÌNH HỆ THỐNG VÀ BÀI VIẾT TẠP CHÍ
    // ---------------------------------------------------------
    public function getAllSettings() {
        $sql = "SELECT * FROM settings";
        $settings = [];
        try {
            $result = $this->conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $settings[$row['setting_key']] = $row;
                }
            }
        } catch (Exception $e) { }
        return $settings;
    }

    public function updateSetting($key, $value) {
        try {
            $stmt = $this->conn->prepare("UPDATE settings SET setting_value=? WHERE setting_key=?");
            $stmt->bind_param("ss", $value, $key);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (Exception $e) { return false; }
    }

    

    // =========================================================
    // CẬP NHẬT USER (thêm tier_id, points)
    // =========================================================
    public function updateUserTier($id, $tier_id, $points) {
        $stmt = $this->conn->prepare("UPDATE users SET tier_id=?, points=? WHERE id=?");
        $stmt->bind_param("iii", $tier_id, $points, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Lấy user kèm tên hạng thành viên
    public function getAllUsersWithTier() {
        $sql = "SELECT u.*, COALESCE(mt.name, 'Bronze') as tier_name, COALESCE(mt.discount_percent, 0) as tier_discount
                FROM users u 
                LEFT JOIN membership_tiers mt ON u.tier_id = mt.id 
                ORDER BY u.id DESC";
        $result = $this->conn->query($sql);
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) $data[] = $row;
        }
        return $data;
    }
}
?>