<?php
class AdminModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

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

    // ---------------------------------------------------------
    // SẢN PHẨM (PRODUCTS)
    // ---------------------------------------------------------
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

    public function addProduct($name, $price, $category, $status, $image) {
        $stmt = $this->conn->prepare("INSERT INTO products (name, price, category, status, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsis", $name, $price, $category, $status, $image);
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

    public function updateProduct($id, $name, $price, $category, $status, $image) {
        $stmt = $this->conn->prepare("UPDATE products SET name=?, price=?, category=?, status=?, image=? WHERE id=?");
        $stmt->bind_param("sdsisi", $name, $price, $category, $status, $image, $id);
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

    public function updatePromotion($id, $old_price) {
        $stmt = $this->conn->prepare("UPDATE products SET old_price = ? WHERE id = ?");
        $stmt->bind_param("di", $old_price, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // ---------------------------------------------------------
    // QUẢN LÝ BANNER QUẢNG CÁO (ĐÃ SỬA LỖI TRẠNG THÁI)
    // ---------------------------------------------------------
    public function getAllBanners() {
        $banners = [];
        try {
            $sql = "SELECT * FROM banners ORDER BY id DESC";
            $result = $this->conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $banners[] = $row;
                }
            }
        } catch (Exception $e) {}
        return $banners;
    }

    public function addBanner($title, $image, $link, $brand_name, $ambassador, $status) {
        try {
            // Đã bổ sung đẩy đủ 6 tham số vào cơ sở dữ liệu
            $stmt = $this->conn->prepare("INSERT INTO banners (title, image, link, brand_name, ambassador, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $title, $image, $link, $brand_name, $ambassador, $status);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (Exception $e) { return false; }
    }

    public function getBannerById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM banners WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $banner = $result->fetch_assoc();
            $stmt->close();
            return $banner;
        } catch (Exception $e) { return null; }
    }

    public function updateBanner($id, $title, $image, $link, $brand_name, $ambassador, $status) {
        try {
            // Đã bổ sung đầy đủ 7 tham số để không bị lệch cột trạng thái
            $stmt = $this->conn->prepare("UPDATE banners SET title=?, image=?, link=?, brand_name=?, ambassador=?, status=? WHERE id=?");
            $stmt->bind_param("sssssii", $title, $image, $link, $brand_name, $ambassador, $status, $id);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (Exception $e) { return false; }
    }

    public function deleteBanner($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM banners WHERE id=?");
            $stmt->bind_param("i", $id);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (Exception $e) { return false; }
    }

    // ---------------------------------------------------------
    // THƯƠNG HIỆU (BRANDS)
    // ---------------------------------------------------------
    public function getAllBrands() {
        $sql = "SELECT * FROM brands ORDER BY id DESC";
        $result = $this->conn->query($sql);
        $brands = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $brands[] = $row;
            }
        }
        return $brands;
    }

    public function addBrand($name, $logo, $banner, $description) {
        $stmt = $this->conn->prepare("INSERT INTO brands (name, logo, banner, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $logo, $banner, $description);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getBrandById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM brands WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $brand = $result->fetch_assoc();
        $stmt->close();
        return $brand;
    }

    public function updateBrand($id, $name, $logo, $banner, $description) {
        $stmt = $this->conn->prepare("UPDATE brands SET name=?, logo=?, banner=?, description=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $logo, $banner, $description, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteBrand($id) {
        $stmt = $this->conn->prepare("DELETE FROM brands WHERE id=?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
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

    public function getOrderById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();
        return $order;
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

    public function addUser($fullname, $email, $password, $role) {
        $stmt = $this->conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $fullname, $email, $password, $role);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateUser($id, $fullname, $email, $role, $password = null) {
        if ($password != null) {
            $stmt = $this->conn->prepare("UPDATE users SET full_name=?, email=?, role=?, password=? WHERE id=?");
            $stmt->bind_param("ssisi", $fullname, $email, $role, $password, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET full_name=?, email=?, role=? WHERE id=?");
            $stmt->bind_param("ssii", $fullname, $email, $role, $id);
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
}
?>