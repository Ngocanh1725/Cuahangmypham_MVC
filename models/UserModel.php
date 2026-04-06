<?php
class UserModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Hàm kiểm tra đăng nhập trong Database
    public function login($email, $password) {
        $email = $this->conn->real_escape_string($email);
        
        // 1. Tìm user theo email
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // 2. Kiểm tra mật khẩu mã hóa (Bcrypt)
            if (password_verify($password, $user['password'])) {
                return $user; // Trả về mảng thông tin user nếu đúng
            }
        }
        return false; // Sai email hoặc sai mật khẩu
    }
}
?>