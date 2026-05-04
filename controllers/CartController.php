<?php
//Xử lý logic giỏ hàng (thêm/sửa/xóa sản phẩm trong Session), 
//quy trình thanh toán (Checkout) và tích hợp API tạo mã QR thanh toán (VietQR).
class CartController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // 1. Hiển thị trang giỏ hàng
    public function index() {
        $cartItems = [];
        $totalPrice = 0;

        if (!empty($_SESSION['cart'])) {
            $ids = array_keys($_SESSION['cart']);
            $str_ids = implode(',', $ids);
            
            $sql = "SELECT * FROM products WHERE id IN ($str_ids)";
            $result = $this->conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $qty = $_SESSION['cart'][$row['id']];
                    $row['qty'] = $qty;
                    $row['subtotal'] = $row['price'] * $qty;
                    $totalPrice += $row['subtotal'];
                    $cartItems[] = $row;
                }
            }
        }
        require_once 'views/cart/index.php';
    }

    // 2. Thêm vào giỏ
    public function add() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]++;
            } else {
                $_SESSION['cart'][$id] = 1;
            }
        }
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
        header("Location: $referer");
        exit();
    }

    // 3. Cập nhật số lượng giỏ hàng
    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['qty'])) {
            foreach ($_POST['qty'] as $id => $quantity) {
                if ($quantity <= 0) {
                    unset($_SESSION['cart'][$id]);
                } else {
                    $_SESSION['cart'][$id] = intval($quantity);
                }
            }
        }
        header("Location: index.php?controller=cart&action=index");
        exit();
    }

    // 4. Xóa 1 món khỏi giỏ
    public function remove() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header("Location: index.php?controller=cart&action=index");
        exit();
    }

    // 5. HIỂN THỊ VÀ XỬ LÝ THANH TOÁN (MỚI)
    public function checkout() {
        // Trống giỏ hàng thì đẩy về lại trang giỏ
        if (empty($_SESSION['cart'])) {
            header("Location: index.php?controller=cart&action=index");
            exit();
        }

        $message = "";
        require_once 'models/OrderModel.php';
        $orderModel = new OrderModel($this->conn);

        // Chuẩn bị dữ liệu giỏ hàng để hiển thị Tóm tắt đơn hàng
        $cartItems = [];
        $totalPrice = 0;
        $ids = array_keys($_SESSION['cart']);
        $str_ids = implode(',', $ids);
        $sql = "SELECT id, name, price, image FROM products WHERE id IN ($str_ids)";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $qty = $_SESSION['cart'][$row['id']];
                $row['qty'] = $qty;
                $row['subtotal'] = $row['price'] * $qty;
                $totalPrice += $row['subtotal'];
                $cartItems[] = $row;
            }
        }

        // Nếu khách hàng bấm "Xác nhận đặt hàng"
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['customer_name'] ?? '';
            $phone = $_POST['customer_phone'] ?? '';
            $address = $_POST['customer_address'] ?? '';
            $payment_method = $_POST['payment_method'] ?? 'COD'; // Lấy phương thức thanh toán
            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

            // 1. Tạo đơn hàng chung
            $order_id = $orderModel->createOrder($user_id, $name, $phone, $address, $totalPrice, $payment_method);
            
            if ($order_id) {
                // 2. Tạo chi tiết đơn hàng cho từng món
                foreach ($cartItems as $item) {
                    $orderModel->createOrderDetail($order_id, $item['id'], $item['price'], $item['qty']);
                }
                
                // 3. Xóa giỏ hàng vì đã đặt thành công
                unset($_SESSION['cart']);
                
                // 4. Chuyển tới trang Hóa Đơn
                header("Location: index.php?controller=cart&action=invoice&id=" . $order_id);
                exit();
            } else {
                $message = "<div class='alert alert-danger'>Hệ thống đang bận, không thể tạo đơn hàng lúc này!</div>";
            }
        }

        // Gọi giao diện Checkout
        require_once 'views/cart/checkout.php';
    }

    // 6. HIỂN THỊ HÓA ĐƠN THÀNH CÔNG (MỚI)
    public function invoice() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            header("Location: index.php");
            exit();
        }

        require_once 'models/OrderModel.php';
        $orderModel = new OrderModel($this->conn);
        
        $order = $orderModel->getOrderById($id);
        if (!$order) {
            die("<div class='text-center mt-5'><h3>Không tìm thấy đơn hàng!</h3><a href='index.php'>Quay lại trang chủ</a></div>");
        }
        
        $orderDetails = $orderModel->getOrderDetails($id);

        require_once 'views/cart/invoice.php';
    }
}
?>