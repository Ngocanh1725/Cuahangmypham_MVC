<?php
require_once 'models/AdminModel.php';
require_once 'helpers/UploadHelper.php';

class AdminController {
    private $adminModel;

    public function __construct($db) {
        $this->adminModel = new AdminModel($db);
    }

    // ---------------------------------------------------------
    // Kiểm tra quyền (Chỉ cho phép Role 1: Admin, Role 2: Staff)
    // ---------------------------------------------------------
    private function checkAuth($module = null) {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
            header("Location: index.php?controller=user&action=login");
            exit();
        }

        if ($_SESSION['role'] == 2) {
            // Luôn lấy quyền mới nhất từ DB
            $db_conn = new mysqli('localhost', 'root', '', 'cosmetics_db');
            if (!$db_conn->connect_error) {
                $stmt = $db_conn->prepare("SELECT permissions FROM users WHERE id = ?");
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res && $res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    $_SESSION['permissions'] = $row['permissions'];
                }
                $stmt->close();
                $db_conn->close();
            }

            if ($module != null) {
                $userPerms = isset($_SESSION['permissions']) ? explode(',', $_SESSION['permissions']) : [];
                if (!in_array($module, $userPerms)) {
                    echo "<script>alert('Bạn không có quyền truy cập chức năng này!'); window.location.href='index.php?controller=admin&action=index';</script>";
                    exit();
                }
            }
        }
    }

    // Upload ảnh giờ được xử lý bởi helpers/UploadHelper.php

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
    public function products() { $this->checkAuth('products');
        $search = $_GET['search'] ?? '';
        $category_id = $_GET['category'] ?? '';
        $brand_id = $_GET['brand_id'] ?? '';

        $products = $this->adminModel->getAllProducts($search, $category_id, $brand_id);
        $brandsList = (require_once 'models/BrandModel.php') ? (new BrandModel($this->adminModel->getConn()))->getAllBrands() : [];
        $categoriesList = $this->adminModel->getAllCategories();
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/products/index.php';
    }

    public function addProduct() { $this->checkAuth('products');
        $message = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
            $brand_id = !empty($_POST['brand_id']) ? intval($_POST['brand_id']) : null;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;
            $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
            $imagePath = "https://via.placeholder.com/300x300?text=No+Image";

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadResult = UploadHelper::uploadFile($_FILES['image'], 'products');
                if ($uploadResult['success']) {
                    $imagePath = $uploadResult['path'];
                } else {
                    $message = "<div class='alert alert-danger'>" . $uploadResult['error'] . "</div>";
                }
            }

            if (empty($message)) {
                if ($this->adminModel->addProduct($name, $price, $category_id, $status, $imagePath, $stock, $brand_id)) {
                    header("Location: index.php?controller=admin&action=products");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi thêm vào CSDL!</div>";
                }
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        $brandsList = (require_once 'models/BrandModel.php') ? (new BrandModel($this->adminModel->getConn()))->getAllBrands() : [];
        $categoriesList = $this->adminModel->getAllCategories();
        require_once 'views/admin/products/add.php';
    }

    public function editProduct() { $this->checkAuth('products');
        $message = "";
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        $product = $this->adminModel->getProductById($id);
        if (!$product) die("Sản phẩm không tồn tại!");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
            $brand_id = !empty($_POST['brand_id']) ? intval($_POST['brand_id']) : null;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;
            $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
            $imagePath = $_POST['current_image'] ?? ''; 

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadResult = UploadHelper::uploadFile($_FILES['image'], 'products');
                if ($uploadResult['success']) {
                    UploadHelper::deleteFile($_POST['current_image'] ?? '');
                    $imagePath = $uploadResult['path'];
                } else {
                    $message = "<div class='alert alert-danger'>" . $uploadResult['error'] . "</div>";
                }
            }

            if (empty($message)) {
                if ($this->adminModel->updateProduct($id, $name, $price, $category_id, $status, $imagePath, $stock, $brand_id)) {
                    header("Location: index.php?controller=admin&action=products");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi cập nhật CSDL!</div>";
                }
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        $brandsList = (require_once 'models/BrandModel.php') ? (new BrandModel($this->adminModel->getConn()))->getAllBrands() : [];
        $categoriesList = $this->adminModel->getAllCategories();
        require_once 'views/admin/products/edit.php';
    }

    public function deleteProduct() { $this->checkAuth('products');
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $product = $this->adminModel->getProductById($id);
            if ($product) {
                UploadHelper::deleteFile($product['image'] ?? '');
            }
            $this->adminModel->deleteProduct($id);
        }
        header("Location: index.php?controller=admin&action=products");
        exit();
    }

    // ---------------------------------------------------------
    // QUẢN LÝ BANNER QUẢNG CÁO
    // ---------------------------------------------------------
    public function banners() { $this->checkAuth('banners');
        $banners = (require_once 'models/BannerModel.php') ? (new BannerModel($this->adminModel->getConn()))->getAllBanners() : [];
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/banners/index.php';
    }

    public function addBanner() { $this->checkAuth('banners');
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
                $uploadResult = UploadHelper::uploadBase64($cropped_image, 'banners');
                if ($uploadResult['success']) {
                    $imagePath = $uploadResult['path'];
                } else {
                    $message = "<div class='alert alert-danger'>" . $uploadResult['error'] . "</div>";
                }
            } elseif (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadResult = UploadHelper::uploadFile($_FILES['image'], 'banners');
                if ($uploadResult['success']) {
                    $imagePath = $uploadResult['path'];
                } else {
                    $message = "<div class='alert alert-danger'>" . $uploadResult['error'] . "</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>Vui lòng tải lên hình ảnh cho Banner!</div>";
            }

            if (empty($message)) {
                if ((require_once 'models/BannerModel.php') ? (new BannerModel($this->adminModel->getConn()))->addBanner($title, $imagePath, $link, $description, $position, $status) : false) {
                    header("Location: index.php?controller=admin&action=banners");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi thêm vào CSDL! Vui lòng kiểm tra lại.</div>";
                }
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/banners/add.php';
    }

    public function editBanner() { $this->checkAuth('banners');
        $message = "";
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        $banner = (require_once 'models/BannerModel.php') ? (new BannerModel($this->adminModel->getConn()))->getBannerById($id) : null;
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
                $uploadResult = UploadHelper::uploadBase64($cropped_image, 'banners');
                if ($uploadResult['success']) {
                    UploadHelper::deleteFile($_POST['current_image'] ?? '');
                    $imagePath = $uploadResult['path'];
                } else {
                    $message = "<div class='alert alert-danger'>" . $uploadResult['error'] . "</div>";
                }
            } elseif (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadResult = UploadHelper::uploadFile($_FILES['image'], 'banners');
                if ($uploadResult['success']) {
                    UploadHelper::deleteFile($_POST['current_image'] ?? '');
                    $imagePath = $uploadResult['path'];
                } else {
                    $message = "<div class='alert alert-danger'>" . $uploadResult['error'] . "</div>";
                }
            }

            if (empty($message)) {
                if ((require_once 'models/BannerModel.php') ? (new BannerModel($this->adminModel->getConn()))->updateBanner($id, $title, $imagePath, $link, $description, $position, $status) : false) {
                    header("Location: index.php?controller=admin&action=banners");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi cập nhật CSDL! Vui lòng thử lại.</div>";
                }
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/banners/edit.php';
    }

    public function deleteBanner() { $this->checkAuth('banners');
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $banner = (require_once 'models/BannerModel.php') ? (new BannerModel($this->adminModel->getConn()))->getBannerById($id) : null;
            if ($banner) {
                UploadHelper::deleteFile($banner['image'] ?? '');
            }
            (require_once 'models/BannerModel.php') ? (new BannerModel($this->adminModel->getConn()))->deleteBanner($id) : false;
        }
        header("Location: index.php?controller=admin&action=banners");
        exit();
    }

    // ---------------------------------------------------------
    // QUẢN LÝ THƯƠNG HIỆU
    // ---------------------------------------------------------
    public function brands() { $this->checkAuth('brands');
        $brands = (require_once 'models/BrandModel.php') ? (new BrandModel($this->adminModel->getConn()))->getAllBrands() : [];
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/brands/index.php';
    }

    public function addBrand() { $this->checkAuth('brands');
        $message = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $logoPath = "https://via.placeholder.com/150?text=No+Logo";
            $bannerPath = "https://via.placeholder.com/1200x300?text=No+Banner";

            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                $uploadLogo = UploadHelper::uploadFile($_FILES['logo'], 'brands');
                if ($uploadLogo['success']) $logoPath = $uploadLogo['path'];
                else $message .= "<div class='alert alert-danger'>Logo: " . $uploadLogo['error'] . "</div>";
            }

            if (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
                $uploadBanner = UploadHelper::uploadFile($_FILES['banner'], 'brands');
                if ($uploadBanner['success']) $bannerPath = $uploadBanner['path'];
                else $message .= "<div class='alert alert-danger'>Banner: " . $uploadBanner['error'] . "</div>";
            }

            if (empty($message)) {
                if ((require_once 'models/BrandModel.php') ? (new BrandModel($this->adminModel->getConn()))->addBrand($name, $logoPath, $bannerPath, $description) : false) {
                    header("Location: index.php?controller=admin&action=brands");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi thêm vào CSDL!</div>";
                }
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/brands/add.php';
    }

    public function editBrand() { $this->checkAuth('brands');
        $message = "";
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        $brand = (require_once 'models/BrandModel.php') ? (new BrandModel($this->adminModel->getConn()))->getBrandById($id) : null;
        if (!$brand) die("Thương hiệu không tồn tại!");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $logoPath = $_POST['current_logo'] ?? ''; 
            $bannerPath = $_POST['current_banner'] ?? ''; 

            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                $uploadLogo = UploadHelper::uploadFile($_FILES['logo'], 'brands');
                if ($uploadLogo['success']) {
                    UploadHelper::deleteFile($_POST['current_logo'] ?? '');
                    $logoPath = $uploadLogo['path'];
                }
            }

            if (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
                $uploadBanner = UploadHelper::uploadFile($_FILES['banner'], 'brands');
                if ($uploadBanner['success']) {
                    UploadHelper::deleteFile($_POST['current_banner'] ?? '');
                    $bannerPath = $uploadBanner['path'];
                }
            }

            if (empty($message)) {
                if ((require_once 'models/BrandModel.php') ? (new BrandModel($this->adminModel->getConn()))->updateBrand($id, $name, $logoPath, $bannerPath, $description) : false) {
                    header("Location: index.php?controller=admin&action=brands");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi cập nhật CSDL!</div>";
                }
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/brands/edit.php';
    }

    public function deleteBrand() { $this->checkAuth('brands');
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $brand = (require_once 'models/BrandModel.php') ? (new BrandModel($this->adminModel->getConn()))->getBrandById($id) : null;
            if ($brand) {
                UploadHelper::deleteFile($brand['logo'] ?? '');
                UploadHelper::deleteFile($brand['banner'] ?? '');
            }
            (require_once 'models/BrandModel.php') ? (new BrandModel($this->adminModel->getConn()))->deleteBrand($id) : false;
        }
        header("Location: index.php?controller=admin&action=brands");
        exit();
    }

    // ---------------------------------------------------------
    // QUẢN LÝ KHUYẾN MÃI
    // ---------------------------------------------------------
    public function promotions() { $this->checkAuth('products');
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
    public function orders() { $this->checkAuth('orders');
        $orders = $this->adminModel->getAllOrders();
        $newOrders = $this->adminModel->getNewOrdersCount(); 
        require_once 'views/admin/orders/index.php';
    }

    // Hiển thị chi tiết đơn hàng
    public function orderDetail() { $this->checkAuth('orders');
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $order = $this->adminModel->getOrderById($id);
            if (!$order) {
                die("Đơn hàng không tồn tại!");
            }
            $orderDetails = $this->adminModel->getOrderDetails($id);
            $newOrders = $this->adminModel->getNewOrdersCount();
            require_once 'views/admin/orders/detail.php';
        } else {
            header("Location: index.php?controller=admin&action=orders");
            exit();
        }
    }

    public function updateOrderStatus() { $this->checkAuth('orders');
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        
        if ($id > 0 && !empty($status)) {
            $order = $this->adminModel->getOrderById($id);
            if ($order && $order['status'] !== 'Đã hủy' && $status === 'Đã hủy') {
                $orderDetails = $this->adminModel->getOrderDetails($id);
                foreach ($orderDetails as $item) {
                    $this->adminModel->increaseStock($item['product_id'], $item['quantity']);
                }
            }
            $this->adminModel->updateOrderStatus($id, $status);
        }
        header("Location: index.php?controller=admin&action=orderDetail&id=" . $id);
        exit();
    }

    // ---------------------------------------------------------
    // QUẢN LÝ TÀI KHOẢN (USER)
    // ---------------------------------------------------------
    public function users() { $this->checkAuth('users');
        if ($_SESSION['role'] != 1) die("Chỉ có Admin cấp cao mới được truy cập quản lý người dùng!");

        $users = $this->adminModel->getAllUsers();
        $newOrders = $this->adminModel->getNewOrdersCount(); 
        require_once 'views/admin/users/index.php';
    }

    public function addUser() { $this->checkAuth('users');
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
        require_once 'views/admin/users/add.php';
    }

    public function editUser() { $this->checkAuth('users');
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
        require_once 'views/admin/users/edit.php';
    }

    public function deleteUser() { $this->checkAuth('users');
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
    public function settings() { $this->checkAuth('settings');
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
    public function posts() { $this->checkAuth('posts');
        $posts = (require_once 'models/PostModel.php') ? (new PostModel($this->adminModel->getConn()))->getAllPosts() : [];
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/posts/index.php';
    }

    // ---------------------------------------------------------
    // QUẢN LÝ MÃ GIẢM GIÁ
    // ---------------------------------------------------------
    public function coupons() {
        $this->checkAuth('coupons');
        require_once 'models/CouponModel.php';
        $couponModel = new CouponModel($this->adminModel->getConn());
        $coupons = $couponModel->getAllCoupons();
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/coupons/index.php';
    }

    public function addCoupon() {
        $this->checkAuth('coupons');
        require_once 'models/CouponModel.php';
        $couponModel = new CouponModel($this->adminModel->getConn());
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($couponModel->addCoupon($_POST['code'], $_POST['type'], $_POST['discount_value'], $_POST['min_order_value'], $_POST['max_discount'], $_POST['usage_limit'], $_POST['start_date'], $_POST['end_date'], $_POST['is_active'], $_POST['description'])) {
                header('Location: index.php?controller=admin&action=coupons');
                exit();
            } else {
                $message = '<div class="alert alert-danger">Lỗi thêm mã giảm giá!</div>';
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/coupons/add.php';
    }

    public function editCoupon() {
        $this->checkAuth('coupons');
        require_once 'models/CouponModel.php';
        $couponModel = new CouponModel($this->adminModel->getConn());
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $coupon = $couponModel->getCouponById($id);
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($couponModel->updateCoupon($id, $_POST['code'], $_POST['type'], $_POST['discount_value'], $_POST['min_order_value'], $_POST['max_discount'], $_POST['usage_limit'], $_POST['start_date'], $_POST['end_date'], $_POST['is_active'], $_POST['description'])) {
                header('Location: index.php?controller=admin&action=coupons');
                exit();
            } else {
                $message = '<div class="alert alert-danger">Lỗi cập nhật mã giảm giá!</div>';
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/coupons/edit.php';
    }

    public function deleteCoupon() {
        $this->checkAuth('coupons');
        require_once 'models/CouponModel.php';
        $couponModel = new CouponModel($this->adminModel->getConn());
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) $couponModel->deleteCoupon($id);
        header('Location: index.php?controller=admin&action=coupons');
        exit();
    }

    // ---------------------------------------------------------
    // QUẢN LÝ HẠNG THÀNH VIÊN
    // ---------------------------------------------------------
    public function tiers() {
        $this->checkAuth('tiers');
        require_once 'models/TierModel.php';
        $tierModel = new TierModel($this->adminModel->getConn());
        $tiers = $tierModel->getAllTiers();
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/tiers/index.php';
    }

    public function addTier() {
        $this->checkAuth('tiers');
        require_once 'models/TierModel.php';
        $tierModel = new TierModel($this->adminModel->getConn());
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($tierModel->addTier($_POST['name'], $_POST['discount_percent'], $_POST['min_points'], $_POST['description'], $_POST['icon_class'])) {
                header('Location: index.php?controller=admin&action=tiers');
                exit();
            } else {
                $message = '<div class="alert alert-danger">Lỗi thêm hạng thành viên!</div>';
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/tiers/add.php';
    }

    public function editTier() {
        $this->checkAuth('tiers');
        require_once 'models/TierModel.php';
        $tierModel = new TierModel($this->adminModel->getConn());
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $tier = $tierModel->getTierById($id);
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($tierModel->updateTier($id, $_POST['name'], $_POST['discount_percent'], $_POST['min_points'], $_POST['description'], $_POST['icon_class'])) {
                header('Location: index.php?controller=admin&action=tiers');
                exit();
            } else {
                $message = '<div class="alert alert-danger">Lỗi cập nhật hạng thành viên!</div>';
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/tiers/edit.php';
    }

    public function deleteTier() {
        $this->checkAuth('tiers');
        require_once 'models/TierModel.php';
        $tierModel = new TierModel($this->adminModel->getConn());
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) $tierModel->deleteTier($id);
        header('Location: index.php?controller=admin&action=tiers');
        exit();
    }

    // ---------------------------------------------------------
    // QUẢN LÝ CỬA HÀNG
    // ---------------------------------------------------------
    public function stores() {
        $this->checkAuth('stores');
        require_once 'models/StoreModel.php';
        $storeModel = new StoreModel($this->adminModel->getConn());
        $stores = $storeModel->getAllStores();
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/stores/index.php';
    }

    public function addStore() {
        $this->checkAuth('stores');
        require_once 'models/StoreModel.php';
        $storeModel = new StoreModel($this->adminModel->getConn());
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($storeModel->addStore($_POST['name'], $_POST['address'], $_POST['phone'], $_POST['email'], $_POST['map_url'])) {
                header('Location: index.php?controller=admin&action=stores');
                exit();
            } else {
                $message = '<div class="alert alert-danger">Lỗi thêm cửa hàng!</div>';
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/stores/add.php';
    }

    public function editStore() {
        $this->checkAuth('stores');
        require_once 'models/StoreModel.php';
        $storeModel = new StoreModel($this->adminModel->getConn());
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $store = $storeModel->getStoreById($id);
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($storeModel->updateStore($id, $_POST['name'], $_POST['address'], $_POST['phone'], $_POST['email'], $_POST['map_url'])) {
                header('Location: index.php?controller=admin&action=stores');
                exit();
            } else {
                $message = '<div class="alert alert-danger">Lỗi cập nhật cửa hàng!</div>';
            }
        }
        $newOrders = $this->adminModel->getNewOrdersCount();
        require_once 'views/admin/stores/edit.php';
    }

    public function deleteStore() {
        $this->checkAuth('stores');
        require_once 'models/StoreModel.php';
        $storeModel = new StoreModel($this->adminModel->getConn());
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) $storeModel->deleteStore($id);
        header('Location: index.php?controller=admin&action=stores');
        exit();
    }
}
?>