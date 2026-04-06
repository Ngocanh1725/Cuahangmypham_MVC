<?php
require_once 'models/AdminModel.php';

class AdminController {
    private $adminModel;

    public function __construct($db) {
        $this->adminModel = new AdminModel($db);
    }

    // Kiểm tra quyền (Hàm dùng chung để đỡ lặp code)
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
            header("Location: index.php?controller=user&action=login");
            exit();
        }
    }

    // Hành động: Hiển thị Bảng điều khiển (Dashboard)
    public function index() {
        $this->checkAuth(); // Kiểm tra bảo mật

        // Lấy số liệu thống kê
        $totalProducts = $this->adminModel->getTotalProducts();
        $newOrders = $this->adminModel->getNewOrdersCount();
        $totalRevenue = $this->adminModel->getTotalRevenue();

        // Gọi View
        require_once 'views/admin/index.php';
    }

    // Hành động MỚI: Quản lý danh sách sản phẩm
    public function products() {
        $this->checkAuth(); // Kiểm tra bảo mật

        // Gọi Model lấy danh sách sản phẩm
        $products = $this->adminModel->getAllProducts();

        // Lấy thêm số lượng đơn hàng mới để hiển thị trên Sidebar (Badge đỏ)
        $newOrders = $this->adminModel->getNewOrdersCount();

        // Gọi View và truyền dữ liệu sang
        require_once 'views/admin/products.php';
    }

    // --- ACTION MỚI: THÊM SẢN PHẨM ---
    public function addProduct() {
        $this->checkAuth();
        $message = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category = $_POST['category'] ?? '';
            $status = $_POST['status'] ?? 1;
            $imagePath = "";

            // Xử lý upload ảnh (Lưu vào thư mục uploads/ ngoài cùng)
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "uploads/";
                if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                $fileName = time() . "_" . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $fileName;
                $allowed_types = ["jpg", "jpeg", "png", "gif"];
                
                if (in_array(strtolower(pathinfo($target_file, PATHINFO_EXTENSION)), $allowed_types)) {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $imagePath = $target_file; // Đường dẫn chuẩn MVC
                    } else {
                        $message = "<div class='alert alert-danger'>Lỗi upload ảnh!</div>";
                    }
                } else {
                    $message = "<div class='alert alert-danger'>Chỉ chấp nhận file ảnh!</div>";
                }
            } else {
                $imagePath = "https://via.placeholder.com/300x300?text=No+Image";
            }

            // Gọi Model để lưu DB
            if (empty($message)) {
                if ($this->adminModel->addProduct($name, $price, $category, $status, $imagePath)) {
                    header("Location: index.php?controller=admin&action=products");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi thêm vào CSDL!</div>";
                }
            }
        }
        
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/add_product.php'; // Gọi View form thêm
    }

    // --- ACTION MỚI: SỬA SẢN PHẨM ---
    public function editProduct() {
        $this->checkAuth();
        $message = "";
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        // Gọi Model lấy dữ liệu sản phẩm cũ hiển thị lên form
        $product = $this->adminModel->getProductById($id);
        if (!$product) die("Sản phẩm không tồn tại!");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category = $_POST['category'] ?? '';
            $status = $_POST['status'] ?? 1;
            $imagePath = $_POST['current_image'] ?? ''; // Giữ ảnh cũ mặc định

            // Nếu có up ảnh mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "uploads/";
                if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                $fileName = time() . "_" . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $fileName;
                
                if (in_array(strtolower(pathinfo($target_file, PATHINFO_EXTENSION)), ["jpg", "jpeg", "png", "gif"])) {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $imagePath = $target_file; // Ghi đè đường dẫn ảnh mới
                    }
                }
            }

            if ($this->adminModel->updateProduct($id, $name, $price, $category, $status, $imagePath)) {
                header("Location: index.php?controller=admin&action=products");
                exit();
            } else {
                $message = "<div class='alert alert-danger'>Lỗi cập nhật CSDL!</div>";
            }
        }
        
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/edit_product.php'; // Gọi View form sửa
    }

    // --- ACTION MỚI: XÓA SẢN PHẨM ---
    public function deleteProduct() {
        $this->checkAuth();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $this->adminModel->deleteProduct($id);
        }
        // Xóa xong đẩy về lại trang danh sách
        header("Location: index.php?controller=admin&action=products");
        exit();
    }

    // --- ACTION MỚI: HIỂN THỊ DANH SÁCH ĐƠN HÀNG ---
    public function orders() {
        $this->checkAuth();
        
        // Gọi Model lấy toàn bộ đơn hàng
        $orders = $this->adminModel->getAllOrders();
        
        // Lấy số lượng đơn mới để hiện thị ở Sidebar
        $newOrders = $this->adminModel->getNewOrdersCount(); 
        
        // Trả về View
        require_once 'views/admin/orders.php';
    }

    // --- ACTION MỚI: CẬP NHẬT TRẠNG THÁI ---
    public function updateOrderStatus() {
        $this->checkAuth();
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        
        if ($id > 0 && !empty($status)) {
            $this->adminModel->updateOrderStatus($id, $status);
        }
        
        // Cập nhật xong tự động quay lại trang danh sách đơn hàng
        header("Location: index.php?controller=admin&action=orders");
        exit();
    }

    // --- ACTION MỚI: XEM CHI TIẾT ĐƠN HÀNG ---
    public function orderDetail() {
        // ... (code cũ)
    }

    // --- CÁC ACTION MỚI: QUẢN LÝ TÀI KHOẢN ---

    // 1. Hiển thị danh sách Tài khoản
    public function users() {
        $this->checkAuth();
        $users = $this->adminModel->getAllUsers();
        $newOrders = $this->adminModel->getNewOrdersCount(); 
        require_once 'views/admin/users.php';
    }

    // 2. Thêm Tài khoản mới (Chỉ Admin)
    public function addUser() {
        $this->checkAuth();
        if ($_SESSION['role'] != 1) die("Bạn không có quyền thực hiện chức năng này!");

        $message = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 0;

            if ($this->adminModel->checkEmailExists($email)) {
                $message = "<div class='alert alert-danger'>Email này đã được sử dụng!</div>";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Mã hóa mật khẩu
                if ($this->adminModel->addUser($fullname, $email, $hashed_password, $role)) {
                    header("Location: index.php?controller=admin&action=users");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi thêm vào CSDL!</div>";
                }
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount(); 
        require_once 'views/admin/add_user.php';
    }

    // 3. Sửa Tài khoản (Chỉ Admin)
    public function editUser() {
        $this->checkAuth();
        if ($_SESSION['role'] != 1) die("Bạn không có quyền thực hiện chức năng này!");

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $user = $this->adminModel->getUserById($id);
        if (!$user) die("Tài khoản không tồn tại!");

        $message = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 0;
            $new_password = $_POST['password'] ?? '';

            if ($this->adminModel->checkEmailExists($email, $id)) {
                $message = "<div class='alert alert-danger'>Email này đã thuộc về người khác!</div>";
            } else {
                $hashed_password = !empty($new_password) ? password_hash($new_password, PASSWORD_DEFAULT) : null;
                
                if ($this->adminModel->updateUser($id, $fullname, $email, $role, $hashed_password)) {
                    header("Location: index.php?controller=admin&action=users");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi cập nhật CSDL!</div>";
                }
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount(); 
        require_once 'views/admin/edit_user.php';
    }

    // 4. Xóa Tài khoản (Chỉ Admin)
    public function deleteUser() {
        $this->checkAuth();
        if ($_SESSION['role'] != 1) die("Bạn không có quyền thực hiện chức năng này!");

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        // Ngăn Admin tự xóa chính mình
        if ($id == $_SESSION['user_id']) {
            die("Không thể tự xóa tài khoản đang đăng nhập!");
        }

        if ($id > 0) {
            $this->adminModel->deleteUser($id);
        }
        header("Location: index.php?controller=admin&action=users");
        exit();
    }
}
?>