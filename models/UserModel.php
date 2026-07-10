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
            
            // Hỗ trợ cả mật khẩu đã hash (chuẩn), mật khẩu thường (test) và MD5 (cũ)
            if (password_verify($password, $user['password']) || 
                $user['password'] === $password || 
                md5($password) === $user['password']) {
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
    public function register($fullname, $email, $phone, $password) {
        if ($this->checkEmailExists($email)) {
            return false; // Email đã tồn tại
        }
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 0; // 0 = Customer
        
        $stmt = $this->conn->prepare("INSERT INTO users (full_name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $fullname, $email, $phone, $hashed_password, $role);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    // [MỚI] Lấy thông tin user theo ID, kèm theo thông tin hạng thành viên
    public function getUserById($id) {
        $sql = "SELECT u.*, 
                       mt.name AS tier_name, mt.discount_percent, mt.color_code, mt.icon 
                FROM users u 
                LEFT JOIN membership_tiers mt ON u.tier_id = mt.id 
                WHERE u.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    // [MỚI] Cập nhật thông tin cá nhân
    public function updateProfile($id, $fullname, $phone, $date_of_birth, $password = null) {
        if ($password != null) {
            $stmt = $this->conn->prepare("UPDATE users SET full_name = ?, phone = ?, date_of_birth = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $fullname, $phone, $date_of_birth, $password, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET full_name = ?, phone = ?, date_of_birth = ? WHERE id = ?");
            $stmt->bind_param("sssi", $fullname, $phone, $date_of_birth, $id);
        }
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateAvatar($id, $avatar_path) {
        $stmt = $this->conn->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        $stmt->bind_param("si", $avatar_path, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // [MỚI] Cập nhật điểm và tính toán lại hạng thành viên
    public function updateUserTier($user_id, $current_tier_id, $newPoints) {
        // 1. Cập nhật số điểm mới
        $stmt = $this->conn->prepare("UPDATE users SET points = ? WHERE id = ?");
        $stmt->bind_param("ii", $newPoints, $user_id);
        $result = $stmt->execute();
        $stmt->close();

        // 2. Kiểm tra xem có đủ điểm lên hạng mới không
        $stmt2 = $this->conn->prepare("SELECT id FROM membership_tiers WHERE min_points <= ? ORDER BY min_points DESC LIMIT 1");
        $stmt2->bind_param("i", $newPoints);
        $stmt2->execute();
        $res = $stmt2->get_result();
        
        if ($res && $res->num_rows > 0) {
            $tier = $res->fetch_assoc();
            $new_tier_id = $tier['id'];
            
            // Nếu hạng thay đổi thì cập nhật tier_id
            if ($new_tier_id != $current_tier_id) {
                $stmt3 = $this->conn->prepare("UPDATE users SET tier_id = ? WHERE id = ?");
                $stmt3->bind_param("ii", $new_tier_id, $user_id);
                $stmt3->execute();
                $stmt3->close();
            }
        }
        $stmt2->close();
        
        return $result;
    }
}
?>