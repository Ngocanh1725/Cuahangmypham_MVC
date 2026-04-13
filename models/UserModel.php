<?php
class UserModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Hàm kiểm tra đăng nhập trong Database sử dụng Prepared Statement
    public function login($email, $password) {
        // Sử dụng prepare statement để ngăn SQL Injection
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        // "s" biểu thị tham số là string
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Kiểm tra mật khẩu mã hóa (Bcrypt)
            if (password_verify($password, $user['password'])) {
                return $user; 
            }
        }
        
        $stmt->close();
        return false; 
    }
}
?>