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
        $qtyToAdd = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

        if ($id > 0 && $qtyToAdd > 0) {
            $sql = "SELECT stock, name FROM products WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            $stmt->close();

            if ($product) {
                $currentQty = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id] : 0;
                $newQty = $currentQty + $qtyToAdd;

                if ($newQty > $product['stock']) {
                    $_SESSION['cart_error'] = "Không thể thêm '$product[name]'. Số lượng tối đa có thể mua là $product[stock].";
                } else {
                    $_SESSION['cart'][$id] = $newQty;
                    $_SESSION['cart_success'] = "Đã thêm '$product[name]' vào giỏ hàng.";
                }
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
                $quantity = intval($quantity);
                if ($quantity <= 0) {
                    unset($_SESSION['cart'][$id]);
                } else {
                    $sql = "SELECT stock, name FROM products WHERE id = ?";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $product = $stmt->get_result()->fetch_assoc();
                    $stmt->close();

                    if ($product) {
                        if ($quantity > $product['stock']) {
                            $_SESSION['cart'][$id] = $product['stock'];
                            $_SESSION['cart_error'] = "Sản phẩm '$product[name]' chỉ còn $product[stock] trong kho. Đã tự động cập nhật.";
                        } else {
                            $_SESSION['cart'][$id] = $quantity;
                        }
                    } else {
                        unset($_SESSION['cart'][$id]);
                    }
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

        // --- FETCH DỮ LIỆU CẤU HÌNH CHO CHECKOUT ---
        require_once 'models/AdminModel.php';
        $adminModel = new AdminModel($this->conn);
        $settings = $adminModel->getAllSettings();
        require_once 'models/StoreModel.php';
        $storeModel = new StoreModel($this->conn);
        $stores = $storeModel->getActiveStores();
        
        $vatEnabled = isset($settings['vat_enabled']['setting_value']) && $settings['vat_enabled']['setting_value'] == '1';
        $vatPercent = $vatEnabled ? floatval($settings['vat_percent']['setting_value'] ?? 10) : 0;
        $shippingDefault = floatval($settings['shipping_fee_default']['setting_value'] ?? 30000);
        $freeShippingMin = floatval($settings['free_shipping_min']['setting_value'] ?? 500000);
        
        // Lấy thông tin user (điểm, tier discount)
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $userData = null;
        if ($user_id) {
            require_once 'models/UserModel.php';
            $userModel = new UserModel($this->conn);
            $userData = $userModel->getUserById($user_id);
        }

        // Nếu khách hàng bấm "Xác nhận đặt hàng"
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['customer_name'] ?? '';
            $phone = $_POST['customer_phone'] ?? '';
            
            // Xử lý Giao hàng hay Lấy tại cửa hàng
            $delivery_method = $_POST['delivery_method'] ?? 'shipping';
            if ($delivery_method == 'pickup') {
                $store_id = intval($_POST['pickup_store'] ?? 0);
                $address = "Lấy tại cửa hàng ID: " . $store_id;
                $shipping_fee = 0;
            } else {
                $address = $_POST['customer_address'] ?? '';
                $shipping_fee = ($totalPrice >= $freeShippingMin) ? 0 : $shippingDefault;
            }
            
            $payment_method = $_POST['payment_method'] ?? 'COD'; 

            // Server-side validation cho Coupon và Points
            $discount_amount = 0;
            $coupon_id = null;
            $points_used = 0;

            // 1. Áp dụng mã giảm giá (Dữ liệu gửi lên từ form ẩn, tạm thời ta sẽ mock up server-side nếu có)
            // Trong thực tế cần có hidden input chứa mã coupon
            $coupon_code = $_POST['coupon_code'] ?? '';
            if (!empty($coupon_code)) {
                require_once 'models/CouponModel.php';
                $couponModel = new CouponModel($this->conn);
                $couponRes = $couponModel->validateCoupon($coupon_code);
                if ($couponRes['valid']) {
                    $coupon = $couponRes['coupon'];
                    $coupon_id = $coupon['id'];
                    if ($totalPrice >= $coupon['min_order_value']) {
                        if ($coupon['type'] == 'percent') {
                            $discount_val = $totalPrice * ($coupon['discount_value'] / 100);
                            if ($coupon['max_discount'] > 0 && $discount_val > $coupon['max_discount']) {
                                $discount_val = $coupon['max_discount'];
                            }
                            $discount_amount += $discount_val;
                        } else {
                            $discount_amount += $coupon['discount_value'];
                        }
                    }
                }
            }

            // 2. Áp dụng hạng thành viên
            if ($userData && $userData['discount_percent'] > 0) {
                $discount_amount += $totalPrice * ($userData['discount_percent'] / 100);
            }

            // 3. Sử dụng điểm
            $use_points = isset($_POST['use_points']) ? 1 : 0;
            if ($use_points && $userData && $userData['points'] > 0) {
                $maxPointsToUse = min($userData['points'], $totalPrice - $discount_amount);
                if ($maxPointsToUse > 0) {
                    $points_used = $maxPointsToUse;
                    $discount_amount += $points_used;
                }
            }

            if ($discount_amount > $totalPrice) $discount_amount = $totalPrice;

            // 4. Tính VAT
            $vat_amount = $vatEnabled ? ($totalPrice - $discount_amount) * ($vatPercent / 100) : 0;
            
            // 5. Tổng cuối
            $finalTotal = $totalPrice - $discount_amount + $vat_amount + $shipping_fee;

            // 6. Tính điểm tích lũy
            $points_per_1000 = floatval($settings['points_per_1000']['setting_value'] ?? 1);
            $points_earned = floor($finalTotal / 1000) * $points_per_1000;

            try {
                $this->conn->begin_transaction();

                // 1. Tạo đơn hàng chung
                $order_id = $orderModel->createOrder($user_id, $name, $phone, $address, $finalTotal, $payment_method, $delivery_method, $shipping_fee, $vat_amount, $discount_amount, $coupon_id, $points_used, $points_earned);
                
                if (!$order_id) {
                    throw new Exception("Hệ thống đang bận, không thể tạo đơn hàng lúc này!");
                }
                
                // 2. Tạo chi tiết đơn hàng cho từng món & Trừ tồn kho
                foreach ($cartItems as $item) {
                    $orderModel->createOrderDetail($order_id, $item['id'], $item['price'], $item['qty']);
                    $orderModel->decreaseStock($item['id'], $item['qty']);
                }

                // 3. Trừ điểm user đã dùng và cộng điểm tích lũy
                if ($user_id && ($points_used > 0 || $points_earned > 0)) {
                    $newPoints = $userData['points'] - $points_used + $points_earned;
                    $userModel->updateUserTier($user_id, $userData['tier_id'], $newPoints);
                }

                // 4. Tăng lượt sử dụng mã giảm giá
                if ($coupon_id) {
                    $stmt = $this->conn->prepare("UPDATE coupons SET used_count = used_count + 1 WHERE id = ?");
                    $stmt->bind_param("i", $coupon_id);
                    $stmt->execute();
                    $stmt->close();
                }
                
                $this->conn->commit();
                
                // 5. Xóa giỏ hàng vì đã đặt thành công
                unset($_SESSION['cart']);
                
                // 6. Chuyển tới trang Hóa Đơn
                header("Location: index.php?controller=cart&action=invoice&id=" . $order_id);
                exit();
                
            } catch (Exception $e) {
                $this->conn->rollback();
                $message = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
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

    // ---------------------------------------------------------
    // AJAX ENDPOINTS CHO MINI CART (BƯỚC 4)
    // ---------------------------------------------------------
    public function ajaxGetCart() {
        header('Content-Type: application/json');
        $cartItems = [];
        $totalPrice = 0;
        $count = 0;

        if (!empty($_SESSION['cart'])) {
            $ids = array_keys($_SESSION['cart']);
            $str_ids = implode(',', $ids);
            
            $sql = "SELECT id, name, price, image FROM products WHERE id IN ($str_ids)";
            $result = $this->conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $qty = $_SESSION['cart'][$row['id']];
                    $row['qty'] = $qty;
                    $totalPrice += $row['price'] * $qty;
                    $count += $qty;
                    $cartItems[] = $row;
                }
            }
        }
        echo json_encode(['items' => $cartItems, 'total' => $totalPrice, 'count' => $count]);
        exit();
    }

    public function ajaxUpdateCart() {
        header('Content-Type: application/json');
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 0;

            if ($id > 0 && $qty > 0) {
                $sql = "SELECT stock FROM products WHERE id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();
                $stmt->close();

                if ($product) {
                    if ($qty > $product['stock']) {
                        echo json_encode(['success' => false, 'message' => 'Vượt quá số lượng tồn kho']);
                        exit();
                    } else {
                        $_SESSION['cart'][$id] = $qty;
                        echo json_encode(['success' => true]);
                        exit();
                    }
                }
            }
        }
        echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật']);
        exit();
    }

    public function ajaxRemoveItem() {
        header('Content-Type: application/json');
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            if ($id > 0 && isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
                echo json_encode(['success' => true]);
                exit();
            }
        }
        echo json_encode(['success' => false]);
        exit();
    }

    public function ajaxGetCartCount() {
        header('Content-Type: application/json');
        $count = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $qty) {
                $count += $qty;
            }
        }
        echo json_encode(['count' => $count]);
        exit();
    }
}
?>