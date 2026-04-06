<?php
// Nhúng Model để xử lý database
require_once 'models/UserModel.php';

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
        // Nếu đã đăng nhập rồi thì phân quyền điều hướng luôn, không hiện form nữa
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) {
                header("Location: index.php?controller=admin&action=index");
            } else {
                header("Location: index.php");
            }
            exit();
        }

        $message = "";
        
        // Xử lý khi người dùng bấm nút Đăng nhập (Submit Form)
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Gọi Model để kiểm tra
            $user = $this->userModel->login($email, $password);

            if ($user) {
                // Đăng nhập thành công -> Lưu thông tin vào Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                // PHÂN QUYỀN ĐIỀU HƯỚNG
                if ($user['role'] == 1 || $user['role'] == 2) {
                    // Role 1 (Quản lý) và Role 2 (Nhân viên) -> Vào trang Admin
                    header("Location: index.php?controller=admin&action=index");
                } else {
                    // Role 0 (Khách hàng) -> Quay lại trang chủ mua sắm
                    header("Location: index.php");
                }
                exit();
            } else {
                $message = "<div class='alert alert-danger text-center'>Email hoặc mật khẩu không chính xác!</div>";
            }
        }

        // Nếu là GET request hoặc đăng nhập sai, hiển thị lại Form
        require_once 'views/users/login.php';
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
}
?>