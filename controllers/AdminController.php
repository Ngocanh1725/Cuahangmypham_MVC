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
    // Hàm xử lý Upload Ảnh
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

    private function handleBase64ImageUpload($base64String) {
        if (empty($base64String)) return false;

        $parts = explode(',', $base64String);
        if (count($parts) !== 2) return ['error' => 'Dữ liệu ảnh không hợp lệ.'];
        
        $imgData = base64_decode($parts[1]);
        if ($imgData === false) return ['error' => 'Giải mã ảnh thất bại.'];

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->buffer($imgData);
        
        $extension = 'jpg'; 
        if ($mime_type === 'image/png') $extension = 'png';
        elseif ($mime_type === 'image/webp') $extension = 'webp';

        $newFileName = md5(uniqid(rand(), true)) . '_cropped.' . $extension;
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        
        $targetFile = $targetDir . $newFileName;

        if (file_put_contents($targetFile, $imgData)) {
            return ['path' => $targetFile];
        } else {
            return ['error' => 'Không thể lưu ảnh đã cắt.'];
        }
    }

    // ---------------------------------------------------------
    // DASHBOARD
    // ---------------------------------------------------------
    public function index() {
        $this->checkAuth();
        $totalProducts = $this->adminModel->getTotalProducts();
        $newOrders = $this->adminModel->getNewOrdersCount();
        $totalRevenue = $this->adminModel->getTotalRevenue();
        $revenueBreakdown = $this->adminModel->getRevenueByPaymentMethod();
        require_once 'views/admin/index.php';
    }

    public function revenueStats() {
        $this->checkAuth();
        $revenues = $this->adminModel->getRevenueDetails();
        $totalRevenue = $this->adminModel->getTotalRevenue();
        $revenueBreakdown = $this->adminModel->getRevenueByPaymentMethod();
        $newOrders = $this->adminModel->getNewOrdersCount(); 
        require_once 'views/admin/revenue_stats.php';
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
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;
            $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
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
                if ($this->adminModel->addProduct($name, $price, $category, $status, $imagePath, $stock)) {
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
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;
            $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
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
                if ($this->adminModel->updateProduct($id, $name, $price, $category, $status, $imagePath, $stock)) {
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
    // QUẢN LÝ BANNER QUẢNG CÁO
    // ---------------------------------------------------------
    public function banners() {
        $this->checkAuth();
        $banners = $this->adminModel->getAllBanners();
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/banners.php';
    }

    public function addBanner() {
        $this->checkAuth();
        $message = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST['title'] ?? '';
            $link = $_POST['link'] ?? '';
            $description = $_POST['description'] ?? '';
            $position = $_POST['position'] ?? 'hero';
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            
            $cropped_image = $_POST['cropped_image'] ?? '';
            $imagePath = "";

            if (!empty($cropped_image)) {
                $uploadResult = $this->handleBase64ImageUpload($cropped_image);
                if (isset($uploadResult['error'])) {
                    $message = "<div class='alert alert-danger'>" . $uploadResult['error'] . "</div>";
                } elseif (isset($uploadResult['path'])) {
                    $imagePath = $uploadResult['path'];
                }
            } elseif (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadResult = $this->handleImageUpload($_FILES['image']);
                if (isset($uploadResult['error'])) {
                    $message = "<div class='alert alert-danger'>" . $uploadResult['error'] . "</div>";
                } elseif (isset($uploadResult['path'])) {
                    $imagePath = $uploadResult['path'];
                }
            } else {
                $message = "<div class='alert alert-danger'>Vui lòng tải lên hình ảnh cho Banner!</div>";
            }

            if (empty($message)) {
                if ($this->adminModel->addBanner($title, $imagePath, $link, $description, $position, $status)) {
                    header("Location: index.php?controller=admin&action=banners");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi thêm vào CSDL! Vui lòng kiểm tra lại.</div>";
                }
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/add_banner.php';
    }

    public function editBanner() {
        $this->checkAuth();
        $message = "";
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        $banner = $this->adminModel->getBannerById($id);
        if (!$banner) die("Banner không tồn tại!");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST['title'] ?? '';
            $link = $_POST['link'] ?? '';
            $description = $_POST['description'] ?? '';
            $position = $_POST['position'] ?? 'hero';
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 0;
            
            $imagePath = $_POST['current_image'] ?? '';
            $cropped_image = $_POST['cropped_image'] ?? '';

            if (!empty($cropped_image)) {
                $uploadResult = $this->handleBase64ImageUpload($cropped_image);
                if (isset($uploadResult['error'])) {
                    $message = "<div class='alert alert-danger'>" . $uploadResult['error'] . "</div>";
                } elseif (isset($uploadResult['path'])) {
                    $imagePath = $uploadResult['path'];
                    if (!empty($_POST['current_image']) && file_exists($_POST['current_image']) && strpos($_POST['current_image'], 'http') === false) {
                        unlink($_POST['current_image']);
                    }
                }
            } elseif (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
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
                if ($this->adminModel->updateBanner($id, $title, $imagePath, $link, $description, $position, $status)) {
                    header("Location: index.php?controller=admin&action=banners");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi cập nhật CSDL! Vui lòng thử lại.</div>";
                }
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/edit_banner.php';
    }

    public function deleteBanner() {
        $this->checkAuth();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $banner = $this->adminModel->getBannerById($id);
            if ($banner && !empty($banner['image']) && file_exists($banner['image']) && strpos($banner['image'], 'http') === false) {
                unlink($banner['image']);
            }
            $this->adminModel->deleteBanner($id);
        }
        header("Location: index.php?controller=admin&action=banners");
        exit();
    }

    // ---------------------------------------------------------
    // QUẢN LÝ THƯƠNG HIỆU
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

            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                $uploadLogo = $this->handleImageUpload($_FILES['logo']);
                if (isset($uploadLogo['error'])) $message .= "<div class='alert alert-danger'>Logo: " . $uploadLogo['error'] . "</div>";
                elseif (isset($uploadLogo['path'])) $logoPath = $uploadLogo['path'];
            }

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

    // Hiển thị chi tiết đơn hàng
    public function orderDetail() {
        $this->checkAuth();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $order = $this->adminModel->getOrderById($id);
            if (!$order) {
                die("Đơn hàng không tồn tại!");
            }
            $orderDetails = $this->adminModel->getOrderDetails($id);
            $newOrders = $this->adminModel->getNewOrdersCount();
            require_once 'views/admin/order_detail.php';
        } else {
            header("Location: index.php?controller=admin&action=orders");
            exit();
        }
    }

    public function updateOrderStatus() {
        $this->checkAuth();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        
        if ($id > 0 && !empty($status)) {
            $this->adminModel->updateOrderStatus($id, $status);
        }
        header("Location: index.php?controller=admin&action=orderDetail&id=" . $id);
        exit();
    }

    // ---------------------------------------------------------
    // QUẢN LÝ TÀI KHOẢN (USER)
    // ---------------------------------------------------------
    public function users() {
        $this->checkAuth();
        if ($_SESSION['role'] != 1) die("Chỉ có Admin cấp cao mới được truy cập quản lý người dùng!");

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

    // ---------------------------------------------------------
    // CẤU HÌNH HỆ THỐNG
    // ---------------------------------------------------------
    public function settings() {
        $this->checkAuth();
        if ($_SESSION['role'] != 1) die("Chỉ có Admin cấp cao mới được chỉnh sửa cấu hình hệ thống!");

        $message = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $site_name = $_POST['site_name'] ?? '';
            $hotline = $_POST['hotline'] ?? '';
            $email = $_POST['email'] ?? '';
            $address = $_POST['address'] ?? '';

            $success = true;
            $success &= $this->adminModel->updateSetting('site_name', $site_name);
            $success &= $this->adminModel->updateSetting('hotline', $hotline);
            $success &= $this->adminModel->updateSetting('email', $email);
            $success &= $this->adminModel->updateSetting('address', $address);

            if ($success) {
                $message = "<div class='alert alert-success'><i class='fas fa-check-circle me-2'></i>Cập nhật cấu hình thành công!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Đã xảy ra lỗi khi lưu cấu hình.</div>";
            }
        }

        $settings = $this->adminModel->getAllSettings();
        $newOrders = $this->adminModel->getNewOrdersCount(); 
        
        require_once 'views/admin/settings.php';
    }

    // ---------------------------------------------------------
    // QUẢN LÝ BÀI VIẾT - TẠP CHÍ
    // ---------------------------------------------------------
    public function posts() {
        $this->checkAuth();
        $posts = $this->adminModel->getAllPosts();
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/posts.php';
    }
}
?>