<?php
// Nhúng Model để xử lý database
require_once 'models/UserModel.php';
// Điều khiển các tính năng Đăng nhập, 
//Đăng xuất và xem/sửa thông tin cá nhân khách hàng

// Bắt buộc phải có thẻ class này bao bọc toàn bộ
class UserController {
    private $userModel;
    private $conn; 

    // Hàm khởi tạo nhận kết nối DB từ index.php truyền vào
    public function __construct($db) {
        $this->userModel = new UserModel($db);
        $this->conn = $db; 
    }

    // --- ACTION ĐĂNG NHẬP ---
    public function login() {
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) {
                header("Location: index.php?controller=admin&action=index");
            } else {
                header("Location: index.php");
            }
            exit();
        }

        $message = "";
        
        if (isset($_SESSION['register_success'])) {
            $message = "<div class='alert alert-success text-center'>" . $_SESSION['register_success'] . "</div>";
            unset($_SESSION['register_success']);
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->login($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] == 1 || $user['role'] == 2) {
                    header("Location: index.php?controller=admin&action=index");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $message = "<div class='alert alert-danger text-center'>Email hoặc mật khẩu không chính xác!</div>";
            }
        }

        require_once 'views/users/login.php';
    }

    // --- ACTION ĐĂNG KÝ ---
    public function register() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit();
        }

        $message = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $fullname = trim($_POST['fullname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($fullname) || empty($email) || empty($password)) {
                $message = "<div class='alert alert-danger text-center'>Vui lòng điền đầy đủ thông tin!</div>";
            } elseif ($password !== $confirm_password) {
                $message = "<div class='alert alert-danger text-center'>Mật khẩu xác nhận không khớp!</div>";
            } else {
                if ($this->userModel->register($fullname, $email, $password)) {
                    $_SESSION['register_success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                    header("Location: index.php?controller=user&action=login");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger text-center'>Email đã được sử dụng! Vui lòng chọn email khác.</div>";
                }
            }
        }

        require_once 'views/users/register.php';
    }

    // --- ACTION ĐĂNG XUẤT ---
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    // --- ACTION XEM LỊCH SỬ MUA HÀNG ---
    public function orders() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=user&action=login");
            exit();
        }
        
        require_once 'models/OrderModel.php';
        $orderModel = new OrderModel($this->conn);
        
        $user_id = $_SESSION['user_id'];
        $orders = $orderModel->getOrdersByUserId($user_id);

        require_once 'views/users/orders.php';
    }

    // --- [MỚI] ACTION HỒ SƠ CÁ NHÂN ---
    public function profile() {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=user&action=login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $message = "";

        // 2. Xử lý khi người dùng ấn nút "Cập nhật"
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $fullname = trim($_POST['fullname'] ?? '');
            $new_password = $_POST['new_password'] ?? '';

            if (empty($fullname)) {
                $message = "<div class='alert alert-warning'>Tên không được để trống!</div>";
            } else {
                // Nếu có nhập mật khẩu mới thì mã hóa, không thì truyền null
                $hashed_password = !empty($new_password) ? password_hash($new_password, PASSWORD_DEFAULT) : null;

                if ($this->userModel->updateProfile($user_id, $fullname, $hashed_password)) {
                    // Cập nhật lại session tên người dùng
                    $_SESSION['full_name'] = $fullname;
                    $message = "<div class='alert alert-success'><i class='fas fa-check-circle me-2'></i>Cập nhật thông tin thành công!</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Có lỗi xảy ra, vui lòng thử lại!</div>";
                }
            }
        }

        // 3. Lấy dữ liệu user hiện tại để hiển thị lên form
        $user = $this->userModel->getUserById($user_id);
        
        // 4. Gọi view hiển thị
        require_once 'views/users/profile.php';
    }
}
?>