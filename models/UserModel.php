<?php
//Xử lý xác thực đăng nhập (password_verify) 
//và cập nhật thông tin hồ sơ cá nhân của khách hàng.
class UserModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Hàm kiểm tra đăng nhập trong Database sử dụng Prepared Statement
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                return $user; 
            }
        }
        
        $stmt->close();
        return false; 
    }

    // Kiểm tra email tồn tại
    public function checkEmailExists($email) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = ($result && $result->num_rows > 0);
        $stmt->close();
        return $exists;
    }

    // Đăng ký tài khoản mới
    public function register($fullname, $email, $password) {
        if ($this->checkEmailExists($email)) {
            return false; // Email đã tồn tại
        }
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 0; // 0 = Customer
        
        $stmt = $this->conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $fullname, $email, $hashed_password, $role);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    // [MỚI] Lấy thông tin user theo ID
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT id, full_name, email, role FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    // [MỚI] Cập nhật thông tin cá nhân
    public function updateProfile($id, $fullname, $password = null) {
        if ($password != null) {
            $stmt = $this->conn->prepare("UPDATE users SET full_name = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssi", $fullname, $password, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET full_name = ? WHERE id = ?");
            $stmt->bind_param("si", $fullname, $id);
        }
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>