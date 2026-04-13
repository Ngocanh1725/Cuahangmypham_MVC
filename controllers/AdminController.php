<?php
require_once 'models/AdminModel.php';

class AdminController {
    private $adminModel;

    public function __construct($db) {
        $this->adminModel = new AdminModel($db);
    }

    // ---------------------------------------------------------
    // Kiểm tra quyền (Chỉ cho phép Role 1: Admin, Role 2: Staff)
    // ---------------------------------------------------------
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
            header("Location: index.php?controller=user&action=login");
            exit();
        }
    }

    // ---------------------------------------------------------
    // Hàm xử lý Upload Ảnh bảo mật 4 lớp
    // ---------------------------------------------------------
    private function handleImageUpload($file) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return false; // Không có file hoặc lỗi đường truyền
        }

        // Lớp 1: Giới hạn dung lượng (Ví dụ: 5MB = 5 * 1024 * 1024 bytes)
        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return ['error' => 'Dung lượng ảnh quá lớn. Tối đa cho phép là 5MB.'];
        }

        // Lớp 2: Kiểm tra đuôi file (Extension)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            return ['error' => 'Chỉ chấp nhận các định dạng ảnh: JPG, JPEG, PNG, GIF, WEBP.'];
        }

        // Lớp 3: Kiểm tra MIME Type thực sự (Đọc cấu trúc file để chống giả mạo đuôi)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            return ['error' => 'Định dạng file không hợp lệ! Phát hiện nghi ngờ giả mạo.'];
        }

        // Lớp 4: Sinh tên file ngẫu nhiên bảo mật (tránh trùng lặp và lỗi font tiếng Việt)
        $newFileName = md5(uniqid(rand(), true)) . '.' . $fileExtension;
        $targetDir = "uploads/";
        
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $targetFile = $targetDir . $newFileName;

        // Lưu file lên Server
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return ['path' => $targetFile];
        } else {
            return ['error' => 'Đã xảy ra lỗi đường truyền, không thể lưu file.'];
        }
    }

    // ---------------------------------------------------------
    // Bảng điều khiển (Dashboard)
    // ---------------------------------------------------------
    public function index() {
        $this->checkAuth();

        // Lấy số liệu thống kê
        $totalProducts = $this->adminModel->getTotalProducts();
        $newOrders = $this->adminModel->getNewOrdersCount();
        $totalRevenue = $this->adminModel->getTotalRevenue();

        require_once 'views/admin/index.php';
    }

    // ---------------------------------------------------------
    // QUẢN LÝ SẢN PHẨM
    // ---------------------------------------------------------
    public function products() {
        $this->checkAuth();

        $products = $this->adminModel->getAllProducts();
        $newOrders = $this->adminModel->getNewOrdersCount();

        require_once 'views/admin/products.php';
    }

    public function addProduct() {
        $this->checkAuth();
        $message = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category = $_POST['category'] ?? '';
            $status = $_POST['status'] ?? 1;
            $imagePath = "https://via.placeholder.com/300x300?text=No+Image";

            // Gọi hàm xử lý upload ảnh bảo mật
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadResult = $this->handleImageUpload($_FILES['image']);
                
                if (isset($uploadResult['error'])) {
                    $message = "<div class='alert alert-danger'>" . $uploadResult['error'] . "</div>";
                } elseif (isset($uploadResult['path'])) {
                    $imagePath = $uploadResult['path']; // Đường dẫn file chuẩn
                }
            }

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
        require_once 'views/admin/add_product.php';
    }

    public function editProduct() {
        $this->checkAuth();
        $message = "";
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        $product = $this->adminModel->getProductById($id);
        if (!$product) die("Sản phẩm không tồn tại!");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category = $_POST['category'] ?? '';
            $status = $_POST['status'] ?? 1;
            $imagePath = $_POST['current_image'] ?? ''; 

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadResult = $this->handleImageUpload($_FILES['image']);
                
                if (isset($uploadResult['error'])) {
                    $message = "<div class='alert alert-danger'>" . $uploadResult['error'] . "</div>";
                } elseif (isset($uploadResult['path'])) {
                    $imagePath = $uploadResult['path']; 
                    
                    // Xóa ảnh cũ trên server nếu không phải là link http (ví dụ ảnh mẫu)
                    if (!empty($_POST['current_image']) && file_exists($_POST['current_image']) && strpos($_POST['current_image'], 'http') === false) {
                        unlink($_POST['current_image']);
                    }
                }
            }

            if (empty($message)) {
                if ($this->adminModel->updateProduct($id, $name, $price, $category, $status, $imagePath)) {
                    header("Location: index.php?controller=admin&action=products");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi cập nhật CSDL!</div>";
                }
            }
        }
        
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/edit_product.php';
    }

    public function deleteProduct() {
        $this->checkAuth();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            // Tùy chọn: Xóa cả file ảnh trên server trước khi xóa khỏi Database
            $product = $this->adminModel->getProductById($id);
            if ($product && !empty($product['image']) && file_exists($product['image']) && strpos($product['image'], 'http') === false) {
                unlink($product['image']);
            }
            
            $this->adminModel->deleteProduct($id);
        }
        header("Location: index.php?controller=admin&action=products");
        exit();
    }

    // ---------------------------------------------------------
    // QUẢN LÝ KHUYẾN MÃI (PROMOTIONS)
    // ---------------------------------------------------------
    public function promotions() {
        $this->checkAuth();

        $products = $this->adminModel->getAllProducts();
        $newOrders = $this->adminModel->getNewOrdersCount();

        require_once 'views/admin/promotions.php';
    }

    public function savePromotion() {
        $this->checkAuth();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $old_price = isset($_POST['old_price']) && $_POST['old_price'] !== '' ? intval($_POST['old_price']) : 0;

            if ($id > 0) {
                $this->adminModel->updatePromotion($id, $old_price);
            }
        }
        
        header("Location: index.php?controller=admin&action=promotions&msg=success");
        exit();
    }

    // ---------------------------------------------------------
    // QUẢN LÝ ĐƠN HÀNG
    // ---------------------------------------------------------
    public function orders() {
        $this->checkAuth();
        
        $orders = $this->adminModel->getAllOrders();
        $newOrders = $this->adminModel->getNewOrdersCount(); 
        
        require_once 'views/admin/orders.php';
    }

    public function updateOrderStatus() {
        $this->checkAuth();
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        
        if ($id > 0 && !empty($status)) {
            $this->adminModel->updateOrderStatus($id, $status);
        }
        
        header("Location: index.php?controller=admin&action=orders");
        exit();
    }

    // ---------------------------------------------------------
    // QUẢN LÝ TÀI KHOẢN (USER)
    // ---------------------------------------------------------
    public function users() {
        $this->checkAuth();
        
        $users = $this->adminModel->getAllUsers();
        $newOrders = $this->adminModel->getNewOrdersCount(); 
        
        require_once 'views/admin/users.php';
    }

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
                $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
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

    public function deleteUser() {
        $this->checkAuth();
        if ($_SESSION['role'] != 1) die("Bạn không có quyền thực hiện chức năng này!");

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        // Ngăn Admin tự xóa tài khoản của chính mình để tránh lỗi
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