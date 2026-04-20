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
            return false;
        }

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            return ['error' => 'Dung lượng ảnh quá lớn. Tối đa cho phép là 5MB.'];
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            return ['error' => 'Chỉ chấp nhận các định dạng ảnh: JPG, JPEG, PNG, GIF, WEBP.'];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            return ['error' => 'Định dạng file không hợp lệ! Phát hiện nghi ngờ giả mạo.'];
        }

        $newFileName = md5(uniqid(rand(), true)) . '.' . $fileExtension;
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $targetFile = $targetDir . $newFileName;

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

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadResult = $this->handleImageUpload($_FILES['image']);
                if (isset($uploadResult['error'])) {
                    $message = "<div class='alert alert-danger'>" . $uploadResult['error'] . "</div>";
                } elseif (isset($uploadResult['path'])) {
                    $imagePath = $uploadResult['path'];
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
    // QUẢN LÝ THƯƠNG HIỆU (BRANDS)
    // ---------------------------------------------------------
    
    public function brands() {
        $this->checkAuth();
        $brands = $this->adminModel->getAllBrands();
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/brands.php';
    }

    public function addBrand() {
        $this->checkAuth();
        $message = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $logoPath = "https://via.placeholder.com/150?text=No+Logo";
            $bannerPath = "https://via.placeholder.com/1200x300?text=No+Banner";

            // Upload Logo
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                $uploadLogo = $this->handleImageUpload($_FILES['logo']);
                if (isset($uploadLogo['error'])) $message .= "<div class='alert alert-danger'>Logo: " . $uploadLogo['error'] . "</div>";
                elseif (isset($uploadLogo['path'])) $logoPath = $uploadLogo['path'];
            }

            // Upload Banner
            if (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
                $uploadBanner = $this->handleImageUpload($_FILES['banner']);
                if (isset($uploadBanner['error'])) $message .= "<div class='alert alert-danger'>Banner: " . $uploadBanner['error'] . "</div>";
                elseif (isset($uploadBanner['path'])) $bannerPath = $uploadBanner['path'];
            }

            if (empty($message)) {
                if ($this->adminModel->addBrand($name, $logoPath, $bannerPath, $description)) {
                    header("Location: index.php?controller=admin&action=brands");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi thêm vào CSDL!</div>";
                }
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/add_brand.php';
    }

    public function editBrand() {
        $this->checkAuth();
        $message = "";
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        $brand = $this->adminModel->getBrandById($id);
        if (!$brand) die("Thương hiệu không tồn tại!");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $logoPath = $_POST['current_logo'] ?? ''; 
            $bannerPath = $_POST['current_banner'] ?? ''; 

            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                $uploadLogo = $this->handleImageUpload($_FILES['logo']);
                if (!isset($uploadLogo['error']) && isset($uploadLogo['path'])) {
                    $logoPath = $uploadLogo['path']; 
                    if (!empty($_POST['current_logo']) && file_exists($_POST['current_logo']) && strpos($_POST['current_logo'], 'http') === false) {
                        unlink($_POST['current_logo']);
                    }
                }
            }

            if (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
                $uploadBanner = $this->handleImageUpload($_FILES['banner']);
                if (!isset($uploadBanner['error']) && isset($uploadBanner['path'])) {
                    $bannerPath = $uploadBanner['path']; 
                    if (!empty($_POST['current_banner']) && file_exists($_POST['current_banner']) && strpos($_POST['current_banner'], 'http') === false) {
                        unlink($_POST['current_banner']);
                    }
                }
            }

            if (empty($message)) {
                if ($this->adminModel->updateBrand($id, $name, $logoPath, $bannerPath, $description)) {
                    header("Location: index.php?controller=admin&action=brands");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi cập nhật CSDL!</div>";
                }
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/edit_brand.php';
    }

    public function deleteBrand() {
        $this->checkAuth();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $brand = $this->adminModel->getBrandById($id);
            if ($brand) {
                if (!empty($brand['logo']) && file_exists($brand['logo']) && strpos($brand['logo'], 'http') === false) unlink($brand['logo']);
                if (!empty($brand['banner']) && file_exists($brand['banner']) && strpos($brand['banner'], 'http') === false) unlink($brand['banner']);
            }
            $this->adminModel->deleteBrand($id);
        }
        header("Location: index.php?controller=admin&action=brands");
        exit();
    }

    // ---------------------------------------------------------
    // QUẢN LÝ KHUYẾN MÃI
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
        if ($id == $_SESSION['user_id']) die("Không thể tự xóa tài khoản đang đăng nhập!");

        if ($id > 0) {
            $this->adminModel->deleteUser($id);
        }
        header("Location: index.php?controller=admin&action=users");
        exit();
    }
}
?>