<?php
// Bắt buộc khởi động session ở dòng đầu tiên để lưu trạng thái đăng nhập, giỏ hàng
session_start();

// 1. Nhúng file cấu hình và kết nối Cơ sở dữ liệu
require_once 'config/Database.php';

// 2. Khởi tạo đối tượng Database và lấy kết nối
$database = new Database();
$db = $database->getConnection();

// 3. Lấy tên Controller và Action từ URL (Mặc định là trang chủ danh sách sản phẩm)
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'product';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// 4. Định tuyến (Routing) - Điều hướng đến đúng Controller
switch ($controllerName) {
    case 'product':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        break;
        
    case 'admin':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController($db);
        break;
        
    case 'cart':
        require_once 'controllers/CartController.php';
        $controller = new CartController($db);
        break;
        
    case 'user':
        require_once 'controllers/UserController.php'; 
        $controller = new UserController($db); 
        break;

    // CONTROLLER MỚI THÊM DÀNH CHO CÁC TRANG TĨNH (Hệ thống cửa hàng, tạp chí, v.v...)
    case 'page':
        require_once 'controllers/PageController.php';
        $controller = new PageController($db);
        break;

    default:
        die("<h2 style='text-align:center; margin-top:50px;'>Lỗi 404: Không tìm thấy Controller (Trang bạn yêu cầu không tồn tại)!</h2>");
}

// 5. Chạy hàm (action) tương ứng trong Controller đã được gọi
if (method_exists($controller, $actionName)) {
    $controller->$actionName();
} else {
    die("<h2 style='text-align:center; margin-top:50px;'>Lỗi 404: Không tìm thấy Action (Hành động không hợp lệ)!</h2>");
}
?>