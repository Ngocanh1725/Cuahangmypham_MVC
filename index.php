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

// ==============================================================
// 4. DYNAMIC ROUTING (ĐỊNH TUYẾN ĐỘNG TỰ ĐỘNG)
// ==============================================================

// Bước 4.1: Chuẩn hóa tên Controller 
// Ví dụ: 'product' -> 'ProductController', 'admin' -> 'AdminController'
$className = ucfirst(strtolower($controllerName)) . 'Controller';

// Bước 4.2: Xác định đường dẫn đến file Controller
$controllerFile = 'controllers/' . $className . '.php';

// Bước 4.3: Kiểm tra xem file Controller có tồn tại không
if (file_exists($controllerFile)) {
    // Nếu có, nạp file đó vào
    require_once $controllerFile;
    
    // Kiểm tra tiếp xem Class có được định nghĩa trong file không
    if (class_exists($className)) {
        // Khởi tạo đối tượng Controller linh hoạt (Truyền $db vào hàm __construct)
        $controller = new $className($db);
        
        // Bước 4.4: Kiểm tra xem hàm (action) có tồn tại trong Controller không
        if (method_exists($controller, $actionName)) {
            // Chạy hàm tương ứng
            $controller->$actionName();
        } else {
            // Lỗi nếu Action không tồn tại
            die("<h2 style='text-align:center; margin-top:50px;'>Lỗi 404: Không tìm thấy Action '{$actionName}' trong '{$className}'!</h2>");
        }
    } else {
        // Lỗi nếu File có nhưng quên đặt tên Class hoặc viết sai chính tả
        die("<h2 style='text-align:center; margin-top:50px;'>Lỗi 500: Lớp '{$className}' không được định nghĩa!</h2>");
    }
} else {
    // Lỗi nếu người dùng nhập sai tên controller trên URL (File không tồn tại)
    die("<h2 style='text-align:center; margin-top:50px;'>Lỗi 404: Không tìm thấy Controller '{$controllerName}' (Trang bạn yêu cầu không tồn tại)!</h2>");
}
?>