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
        if (isset($_POST['buy_now']) && $_POST['buy_now'] == '1') {
            header("Location: index.php?controller=cart&action=checkout");
            exit();
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

    // 5. HIỂN THỊ VÀ XỬ LÝ THANH TOÁN
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
            $name  = $_POST['customer_name'] ?? '';
            $phone = $_POST['customer_phone'] ?? '';
            $email = $_POST['customer_email'] ?? '';
            $note  = $_POST['note'] ?? '';

            // Xử lý Giao hàng hay Lấy tại cửa hàng
            $delivery_method = $_POST['delivery_method'] ?? 'shipping';
            $store_id = null;
            $city = $district = $ward = '';

            if ($delivery_method == 'pickup') {
                $store_id = intval($_POST['store_id'] ?? 0);
                $address = "Lấy tại cửa hàng ID: " . $store_id;
                $shipping_fee = 0;
            } else {
                // Ghép địa chỉ từ các trường chi tiết
                $street   = $_POST['customer_street'] ?? '';
                $ward     = $_POST['customer_ward'] ?? '';
                $district = $_POST['customer_district'] ?? '';
                $city     = $_POST['customer_city'] ?? '';
                $address  = trim("$street, $ward, $district, $city");
                $shipping_fee = ($totalPrice >= $freeShippingMin) ? 0 : $shippingDefault;
            }

            $payment_method = $_POST['payment_method'] ?? 'COD';

            // --- Tính toán chiết khấu (server-side) ---
            $coupon_discount = 0;  // Riêng giảm từ coupon
            $member_discount = 0;  // Riêng giảm từ hạng thành viên
            $coupon_id   = null;
            $coupon_code_used = '';
            $points_used = 0;

            // 1. Áp dụng mã giảm giá
            $coupon_code = $_POST['coupon_code'] ?? '';
            if (!empty($coupon_code)) {
                require_once 'models/CouponModel.php';
                $couponModel = new CouponModel($this->conn);
                $couponRes = $couponModel->validateCoupon($coupon_code);
                if ($couponRes['valid']) {
                    $coupon = $couponRes['coupon'];
                    $coupon_id = $coupon['id'];
                    $coupon_code_used = $coupon_code;
                    if ($totalPrice >= $coupon['min_order_value']) {
                        if ($coupon['type'] == 'percent') {
                            $discount_val = $totalPrice * ($coupon['discount_value'] / 100);
                            if ($coupon['max_discount'] > 0 && $discount_val > $coupon['max_discount']) {
                                $discount_val = $coupon['max_discount'];
                            }
                            $coupon_discount = $discount_val;
                        } else {
                            $coupon_discount = $coupon['discount_value'];
                        }
                    }
                }
            }

            // 2. Áp dụng hạng thành viên
            if ($userData && $userData['discount_percent'] > 0) {
                $member_discount = $totalPrice * ($userData['discount_percent'] / 100);
            }

            // 3. Sử dụng điểm
            $use_points = isset($_POST['use_points']) ? 1 : 0;
            $discount_amount = $coupon_discount + $member_discount;
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

                // 1. Tạo đơn hàng — truyền mảng dữ liệu đầy đủ
                $order_id = $orderModel->createOrder([
                    'user_id'          => $user_id,
                    'customer_name'    => $name,
                    'customer_phone'   => $phone,
                    'customer_email'   => $email,
                    'customer_address' => $address,
                    'customer_city'    => $city,
                    'customer_district'=> $district,
                    'customer_ward'    => $ward,
                    'subtotal'         => $totalPrice,
                    'discount_amount'  => $discount_amount,
                    'coupon_discount'  => $coupon_discount,
                    'member_discount'  => $member_discount,
                    'coupon_id'        => $coupon_id,
                    'coupon_code'      => $coupon_code_used,
                    'points_used'      => $points_used,
                    'points_earned'    => $points_earned,
                    'shipping_fee'     => $shipping_fee,
                    'vat_amount'       => $vat_amount,
                    'total_price'      => $finalTotal,
                    'payment_method'   => $payment_method,
                    'delivery_method'  => $delivery_method,
                    'store_id'         => $store_id,
                    'note'             => $note,
                ]);

                if (!$order_id) {
                    throw new Exception("Hệ thống đang bận, không thể tạo đơn hàng lúc này!");
                }

                // Gọi InventoryModel để trừ kho và ghi log
                require_once 'models/InventoryModel.php';
                $inventoryModel = new InventoryModel($this->conn);

                // Nếu chọn nhận tại cửa hàng, kiểm tra lại tồn kho một lần nữa trên server để đảm bảo an toàn
                if ($delivery_method === 'pickup' && $store_id > 0) {
                    foreach ($cartItems as $item) {
                        $storeStock = $inventoryModel->getStoreStock($store_id, $item['id']);
                        if ($storeStock < $item['qty']) {
                            throw new Exception("Sản phẩm '{$item['name']}' không đủ hàng tại cửa hàng này. Vui lòng chọn cửa hàng khác.");
                        }
                    }
                }

                // 2. Tạo chi tiết đơn hàng cho từng món & Trừ tồn kho
                foreach ($cartItems as $item) {
                    $orderModel->createOrderDetail($order_id, $item['id'], $item['price'], $item['qty']);
                    
                    if ($delivery_method === 'pickup' && $store_id > 0) {
                        $reason = "Khách đặt hàng (Nhận tại CH) - Đơn #" . $order_id;
                        $inventoryModel->decreaseStoreStock($store_id, $item['id'], $item['qty'], $reason);
                    } else {
                        // Giao hàng tận nơi -> trừ kho tổng
                        $reason = "Khách đặt hàng (Giao tận nơi) - Đơn #" . $order_id;
                        $inventoryModel->deductStock($item['id'], $item['qty'], $reason);
                    }
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

                // 5. Lưu thông tin hóa đơn VAT (nếu khách yêu cầu)
                $vat_requested = isset($_POST['vat_requested']) ? 1 : 0;
                if ($vat_requested) {
                    $vat_company      = $_POST['vat_company_name'] ?? '';
                    $vat_tax_code     = $_POST['vat_tax_code'] ?? '';
                    $vat_company_addr = $_POST['vat_company_address'] ?? '';
                    if (!empty($vat_company) && !empty($vat_tax_code)) {
                        $orderModel->saveVatInvoice($order_id, $vat_company, $vat_tax_code, $vat_company_addr);
                    }
                }

                $this->conn->commit();

                // 6. Xóa giỏ hàng vì đã đặt thành công
                unset($_SESSION['cart']);

                // 6.5. Gửi email xác nhận (Order Receipt)
                if (!empty($email)) {
                    require_once 'helpers/MailHelper.php';
                    MailHelper::sendOrderReceipt($order_id, $email, $name, $cartItems, $finalTotal, $payment_method);
                }

                // 7. Chuyển tới trang Hóa Đơn
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

    // 6. HIỂN THỊ HÓA ĐƠN THÀNH CÔNG
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
    // AJAX ENDPOINTS CHO MINI CART
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

    // ---------------------------------------------------------
    // AJAX: Tính toán TOÀN BỘ chi phí Checkout (Server-side)
    // Endpoint: index.php?controller=cart&action=ajaxCalculateTotal
    // ---------------------------------------------------------
    public function ajaxCalculateTotal() {
        header('Content-Type: application/json');

        // 1. Lấy giỏ hàng từ Session → tính subtotal
        $subtotal = 0;
        $itemCount = 0;
        if (!empty($_SESSION['cart'])) {
            $ids = array_keys($_SESSION['cart']);
            $str_ids = implode(',', array_map('intval', $ids));
            $sql = "SELECT id, price FROM products WHERE id IN ($str_ids)";
            $result = $this->conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $qty = $_SESSION['cart'][$row['id']];
                    $subtotal += $row['price'] * $qty;
                    $itemCount += $qty;
                }
            }
        }

        if ($subtotal <= 0) {
            echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống.']);
            exit();
        }

        // 2. Lấy Settings từ DB
        require_once 'models/AdminModel.php';
        $adminModel = new AdminModel($this->conn);
        $settings = $adminModel->getAllSettings();

        $vatEnabled      = isset($settings['vat_enabled']['setting_value']) && $settings['vat_enabled']['setting_value'] == '1';
        $vatPercent       = $vatEnabled ? floatval($settings['vat_percent']['setting_value'] ?? 10) : 0;
        $shippingDefault  = floatval($settings['shipping_fee_default']['setting_value'] ?? 30000);
        $freeShippingMin  = floatval($settings['free_shipping_min']['setting_value'] ?? 500000);

        // 3. Delivery method → Shipping fee
        $delivery_method = $_POST['delivery_method'] ?? 'shipping';
        $shipping_fee = 0;
        if ($delivery_method === 'shipping') {
            $shipping_fee = ($subtotal >= $freeShippingMin) ? 0 : $shippingDefault;
        }

        // 4. Coupon
        $coupon_code = isset($_POST['coupon_code']) ? trim(strtoupper($_POST['coupon_code'])) : '';
        $coupon_discount = 0;
        $coupon_message = '';
        $coupon_valid = false;

        if (!empty($coupon_code)) {
            require_once 'models/CouponModel.php';
            $couponModel = new CouponModel($this->conn);
            $couponRes = $couponModel->validateCoupon($coupon_code);

            if ($couponRes['valid']) {
                $coupon = $couponRes['coupon'];

                if ($subtotal < $coupon['min_order_value']) {
                    $minFmt = number_format($coupon['min_order_value']);
                    $coupon_message = "Đơn hàng tối thiểu {$minFmt}đ để sử dụng mã này.";
                } else {
                    $coupon_valid = true;
                    if ($coupon['type'] === 'percent') {
                        $coupon_discount = $subtotal * ($coupon['discount_value'] / 100);
                        if (!empty($coupon['max_discount']) && $coupon_discount > $coupon['max_discount']) {
                            $coupon_discount = $coupon['max_discount'];
                        }
                        $desc = "Giảm " . intval($coupon['discount_value']) . "%";
                        if (!empty($coupon['max_discount'])) {
                            $desc .= " (tối đa " . number_format($coupon['max_discount']) . "đ)";
                        }
                    } else {
                        $coupon_discount = $coupon['discount_value'];
                        $desc = "Giảm " . number_format($coupon_discount) . "đ";
                    }
                    $coupon_message = "Áp dụng mã {$coupon_code} thành công! {$desc}";
                }
            } else {
                $coupon_message = $couponRes['message'];
            }
        }

        // 5. Member Tier Discount
        $member_discount = 0;
        $tier_name = '';
        $tier_percent = 0;
        $user_id = $_SESSION['user_id'] ?? null;
        if ($user_id) {
            require_once 'models/UserModel.php';
            $userModel = new UserModel($this->conn);
            $userData = $userModel->getUserById($user_id);
            if ($userData && $userData['discount_percent'] > 0) {
                $tier_percent = $userData['discount_percent'];
                $member_discount = $subtotal * ($tier_percent / 100);
                $tier_name = $userData['tier_name'] ?? '';
            }
        }

        // 6. Tổng giảm giá
        $total_discount = $coupon_discount + $member_discount;
        if ($total_discount > $subtotal) $total_discount = $subtotal;

        // 7. VAT (trên tổng sau giảm giá)
        $vat_amount = 0;
        if ($vatEnabled) {
            $vat_amount = ($subtotal - $total_discount) * ($vatPercent / 100);
        }

        // 8. Tổng cuối cùng
        $final_total = $subtotal - $total_discount + $vat_amount + $shipping_fee;
        if ($final_total < 0) $final_total = 0;

        // 9. Format tiền VND
        $fmt = function($n) { return number_format(round($n)); };

        echo json_encode([
            'success'            => true,
            'item_count'         => $itemCount,
            'subtotal'           => $subtotal,
            'subtotal_fmt'       => $fmt($subtotal) . 'đ',
            'shipping_fee'       => $shipping_fee,
            'shipping_fmt'       => $shipping_fee > 0 ? $fmt($shipping_fee) . 'đ' : 'Miễn phí',
            'shipping_free'      => $shipping_fee == 0,
            'coupon_valid'       => $coupon_valid,
            'coupon_message'     => $coupon_message,
            'coupon_discount'    => $coupon_discount,
            'coupon_discount_fmt'=> $coupon_discount > 0 ? '-' . $fmt($coupon_discount) . 'đ' : '',
            'member_discount'    => $member_discount,
            'member_discount_fmt'=> $member_discount > 0 ? '-' . $fmt($member_discount) . 'đ' : '',
            'tier_name'          => $tier_name,
            'tier_percent'       => $tier_percent,
            'vat_enabled'        => $vatEnabled,
            'vat_percent'        => $vatPercent,
            'vat_amount'         => $vat_amount,
            'vat_amount_fmt'     => $vat_amount > 0 ? $fmt($vat_amount) . 'đ' : '0đ',
            'total_discount'     => $total_discount,
            'final_total'        => $final_total,
            'final_total_fmt'    => $fmt($final_total) . 'đ',
        ]);
        exit();
    }

    // Ajax kiểm tra tồn kho tại cơ sở (khi người dùng chọn store dropdown)
    public function ajaxCheckStoreStock() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') exit;
        
        $store_id = intval($_POST['store_id'] ?? 0);
        $cartItems = isset($_SESSION['cart']) ? array_values($_SESSION['cart']) : [];

        if (empty($cartItems) || $store_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
            exit;
        }

        require_once 'models/InventoryModel.php';
        $inventoryModel = new InventoryModel($this->conn);

        $outOfStockItems = [];
        foreach ($cartItems as $item) {
            $storeStock = $inventoryModel->getStoreStock($store_id, $item['id']);
            if ($storeStock < $item['qty']) {
                $outOfStockItems[] = [
                    'name' => $item['name'],
                    'available' => $storeStock,
                    'requested' => $item['qty']
                ];
            }
        }

        if (empty($outOfStockItems)) {
            echo json_encode(['success' => true, 'message' => 'Tất cả sản phẩm đều còn hàng tại cơ sở này.']);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Một số sản phẩm không đủ hàng tại cơ sở này.',
                'items' => $outOfStockItems
            ]);
        }
        exit;
    }
}
?>