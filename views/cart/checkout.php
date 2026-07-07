<?php 
$pageTitle = "Thanh toán - Glow Beauty"; 
include 'views/layout/header.php'; 
?>

<!-- ============================
     STYLES RIÊNG CHO CHECKOUT
     ============================ -->
<style>
    .checkout-page {
        background: #fcfcfc;
        min-height: 100vh;
        padding: 40px 0;
    }
    .checkout-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        background: #fff;
        margin-bottom: 24px;
        overflow: hidden;
    }
    .checkout-header {
        background: #fff;
        padding: 20px 24px;
        border-bottom: 1px solid #f0f0f0;
    }
    .checkout-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        margin: 0;
        color: #1a1a1a;
    }
    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px 16px;
        border: 1px solid #e0e0e0;
        background: #fdfdfd;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--brand-color);
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.15);
        background: #fff;
    }
    .delivery-option {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .delivery-option:hover {
        border-color: #ff9999;
        background: #fffafa;
    }
    .delivery-option.active {
        border-color: var(--brand-color);
        background: #fff0f0;
    }
    .delivery-option input[type="radio"] {
        display: none;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        color: #555;
    }
    .summary-total {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--brand-color);
        border-top: 2px dashed #e0e0e0;
        padding-top: 16px;
        margin-top: 16px;
    }
    .btn-apply {
        border-radius: 0 10px 10px 0;
        padding: 0 20px;
    }
    .input-group .form-control {
        border-radius: 10px 0 0 10px;
    }
</style>

<div class="checkout-page">
    <div class="container" style="max-width: 1100px;">
        <h2 class="text-center mb-4" style="font-family: 'Playfair Display', serif; font-weight: 800;">Hoàn Tất Đơn Hàng</h2>
        
        <?php if(!empty($message)) echo $message; ?>

        <form method="POST" id="checkoutForm">
            <div class="row">
                <!-- CỘT TRÁI: THÔNG TIN GIAO HÀNG & THANH TOÁN -->
                <div class="col-lg-7">
                    
                    <!-- 1. Phương thức nhận hàng -->
                    <div class="checkout-card">
                        <div class="checkout-header">
                            <h5 class="checkout-title"><i class="fas fa-truck text-brand me-2"></i>1. Phương Thức Nhận Hàng</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="delivery-option w-100 active" id="lblDeliveryShipping">
                                        <input type="radio" name="delivery_method" value="shipping" checked onchange="toggleDeliveryMethod('shipping')">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-motorcycle fa-2x text-brand me-3"></i>
                                            <div>
                                                <h6 class="fw-bold mb-1">Giao tận nơi</h6>
                                                <small class="text-muted">Nhận hàng tại nhà</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="delivery-option w-100" id="lblDeliveryPickup">
                                        <input type="radio" name="delivery_method" value="pickup" onchange="toggleDeliveryMethod('pickup')">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-store fa-2x text-success me-3"></i>
                                            <div>
                                                <h6 class="fw-bold mb-1">Nhận tại cửa hàng</h6>
                                                <small class="text-muted">Đến lấy trực tiếp</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Form Giao Tận Nơi -->
                            <div id="shippingForm">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Họ và Tên</label>
                                        <input type="text" name="customer_name" class="form-control" value="<?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Số điện thoại</label>
                                        <input type="text" name="customer_phone" class="form-control" value="<?= htmlspecialchars($userData['phone'] ?? '') ?>" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label fw-bold small text-muted text-uppercase">Địa chỉ giao hàng chi tiết</label>
                                    <textarea name="customer_address" class="form-control" rows="3" required placeholder="Số nhà, đường, phường/xã, quận/huyện..."></textarea>
                                </div>
                            </div>

                            <!-- Form Nhận Cửa Hàng -->
                            <div id="pickupForm" style="display: none;">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Họ và Tên người nhận</label>
                                        <input type="text" name="pickup_name" class="form-control" value="<?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Số điện thoại</label>
                                        <input type="text" name="pickup_phone" class="form-control" value="<?= htmlspecialchars($userData['phone'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Chọn cửa hàng</label>
                                    <select name="pickup_store" class="form-select">
                                        <option value="">-- Vui lòng chọn cửa hàng --</option>
                                        <?php if(!empty($stores)) foreach($stores as $s): ?>
                                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?> - <?= htmlspecialchars($s['address']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="alert alert-info py-2 small mb-0"><i class="fas fa-info-circle me-2"></i>Bạn sẽ không mất phí vận chuyển khi nhận tại cửa hàng.</div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Phương thức thanh toán -->
                    <div class="checkout-card">
                        <div class="checkout-header">
                            <h5 class="checkout-title"><i class="fas fa-wallet text-success me-2"></i>2. Phương Thức Thanh Toán</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-check mb-3 p-3 border rounded-3 bg-light">
                                <input class="form-check-input ms-1" type="radio" name="payment_method" id="pay_cod" value="COD" checked>
                                <label class="form-check-label fw-bold ms-3" for="pay_cod">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i> Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>
                            <div class="form-check p-3 border rounded-3 bg-light">
                                <input class="form-check-input ms-1" type="radio" name="payment_method" id="pay_qr" value="QR">
                                <label class="form-check-label fw-bold ms-3" for="pay_qr">
                                    <i class="fas fa-qrcode text-primary me-2"></i> Chuyển khoản (Quét mã QR)
                                </label>
                                <div class="small text-muted ms-4 mt-2">Mã QR VietQR sẽ được tạo tự động sau khi đặt hàng thành công.</div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- CỘT PHẢI: TÓM TẮT & KHUYẾN MÃI -->
                <div class="col-lg-5">
                    <div class="checkout-card sticky-top" style="top: 20px;">
                        <div class="checkout-header bg-light">
                            <h5 class="checkout-title">Tóm Tắt Đơn Hàng</h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Danh sách sản phẩm -->
                            <div class="cart-items mb-4" style="max-height: 250px; overflow-y: auto;">
                                <?php foreach ($cartItems as $item): ?>
                                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;"><?= htmlspecialchars($item['name']) ?></h6>
                                            <small class="text-muted">x<?= $item['qty'] ?></small>
                                        </div>
                                        <div class="fw-bold text-dark">
                                            <?= number_format($item['subtotal']) ?>đ
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Khuyến mãi & Điểm -->
                            <div class="mb-4">
                                <div class="input-group mb-2">
                                    <input type="text" id="couponCode" class="form-control text-uppercase" placeholder="Nhập mã ưu đãi">
                                    <button class="btn btn-dark btn-apply fw-bold" type="button" onclick="applyCoupon()">ÁP DỤNG</button>
                                </div>
                                <div id="couponMessage" class="small mb-3"></div>

                                <?php if($userData): ?>
                                    <div class="bg-light p-3 rounded-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold"><i class="fas fa-coins text-warning me-2"></i>Điểm của bạn: <?= number_format($userData['points']) ?></span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="usePoints" name="use_points" value="1" onchange="calculateTotal()">
                                                <label class="form-check-label small" for="usePoints">Dùng điểm</label>
                                            </div>
                                        </div>
                                        <?php if($userData['tier_name']): ?>
                                            <div class="small text-success fw-bold"><i class="fas fa-star me-1"></i> Thành viên <?= $userData['tier_name'] ?> - Giảm <?= $userData['discount_percent'] ?>%</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <input type="hidden" name="coupon_code" id="hiddenCouponCode" value="">

                            <!-- Tổng kết -->
                            <div class="summary-item">
                                <span>Tạm tính (<?= count($cartItems) ?> sp)</span>
                                <span class="fw-bold text-dark" id="txtSubtotal"><?= number_format($totalPrice) ?>đ</span>
                            </div>
                            
                            <?php if($vatEnabled): ?>
                            <div class="summary-item">
                                <span>Thuế VAT (<?= $vatPercent ?>%)</span>
                                <span class="fw-bold text-dark" id="txtVat">0đ</span>
                            </div>
                            <?php endif; ?>

                            <div class="summary-item">
                                <span>Phí vận chuyển</span>
                                <span class="fw-bold text-dark" id="txtShipping">0đ</span>
                            </div>

                            <div class="summary-item text-success" id="rowDiscount" style="display:none;">
                                <span>Khuyến mãi / Giảm giá</span>
                                <span class="fw-bold" id="txtDiscount">-0đ</span>
                            </div>

                            <div class="summary-item summary-total align-items-center">
                                <span>TỔNG CỘNG</span>
                                <span id="txtFinalTotal"><?= number_format($totalPrice) ?>đ</span>
                            </div>

                            <button type="submit" class="btn btn-brand w-100 py-3 mt-4 fw-bold fs-5 rounded-pill shadow">ĐẶT HÀNG NGAY</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Biến lưu trữ data từ PHP để JS tính toán
const cartTotal = <?= $totalPrice ?>;
const shippingDefault = <?= $shippingDefault ?>;
const freeShippingMin = <?= $freeShippingMin ?>;
const vatEnabled = <?= $vatEnabled ? 'true' : 'false' ?>;
const vatPercent = <?= $vatPercent ?>;
const userPoints = <?= $userData ? $userData['points'] : 0 ?>;
const tierDiscount = <?= $userData ? $userData['discount_percent'] : 0 ?>;

let currentShipping = shippingDefault;
let currentDiscount = 0;
let couponDiscount = 0;

function toggleDeliveryMethod(method) {
    document.getElementById('lblDeliveryShipping').classList.remove('active');
    document.getElementById('lblDeliveryPickup').classList.remove('active');
    
    if (method === 'shipping') {
        document.getElementById('lblDeliveryShipping').classList.add('active');
        document.getElementById('shippingForm').style.display = 'block';
        document.getElementById('pickupForm').style.display = 'none';
        
        // Disable pickup fields so they don't block HTML5 validation
        document.querySelector('select[name="pickup_store"]').removeAttribute('required');
    } else {
        document.getElementById('lblDeliveryPickup').classList.add('active');
        document.getElementById('shippingForm').style.display = 'none';
        document.getElementById('pickupForm').style.display = 'block';
        
        document.querySelector('select[name="pickup_store"]').setAttribute('required', 'required');
    }
    
    calculateTotal();
}

function applyCoupon() {
    // Demo: Ở step này chưa gọi Ajax API check mã, ta giả lập giảm 50k nếu mã là GLOW50
    const code = document.getElementById('couponCode').value.trim().toUpperCase();
    const msg = document.getElementById('couponMessage');
    if(code === 'GLOW50') {
        couponDiscount = 50000;
        document.getElementById('hiddenCouponCode').value = 'GLOW50';
        msg.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Áp dụng mã GLOW50 thành công (Giảm 50k)</span>';
    } else if(code !== '') {
        couponDiscount = 0;
        document.getElementById('hiddenCouponCode').value = '';
        msg.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Mã không hợp lệ hoặc đã hết hạn</span>';
    } else {
        couponDiscount = 0;
        document.getElementById('hiddenCouponCode').value = '';
        msg.innerHTML = '';
    }
    calculateTotal();
}

function calculateTotal() {
    // 1. Tính phí vận chuyển
    let method = document.querySelector('input[name="delivery_method"]:checked').value;
    if (method === 'pickup') {
        currentShipping = 0;
    } else {
        currentShipping = (cartTotal >= freeShippingMin) ? 0 : shippingDefault;
    }

    // 2. Tính giảm giá (Tier + Coupon + Points)
    let discount = 0;
    // - Hạng thành viên
    if (tierDiscount > 0) {
        discount += cartTotal * (tierDiscount / 100);
    }
    // - Mã giảm giá
    discount += couponDiscount;
    // - Dùng điểm (Giả sử 1 điểm = 1đ)
    let usePointsCb = document.getElementById('usePoints');
    if (usePointsCb && usePointsCb.checked) {
        // Chỉ dùng tối đa số điểm bằng số tiền đơn hàng
        let maxPointsToUse = Math.min(userPoints, cartTotal - discount);
        discount += maxPointsToUse;
    }

    // Đảm bảo giảm giá không vượt quá tổng tiền hàng
    if (discount > cartTotal) discount = cartTotal;

    // 3. Tính VAT trên (Tiền hàng - Giảm giá)
    let vatAmount = 0;
    if (vatEnabled) {
        vatAmount = (cartTotal - discount) * (vatPercent / 100);
    }

    // 4. Tổng cuối cùng
    let finalTotal = cartTotal - discount + vatAmount + currentShipping;

    // 5. Hiển thị UI
    document.getElementById('txtShipping').innerText = currentShipping > 0 ? new Intl.NumberFormat('vi-VN').format(currentShipping) + 'đ' : 'Miễn phí';
    
    if (vatEnabled) {
        document.getElementById('txtVat').innerText = new Intl.NumberFormat('vi-VN').format(vatAmount) + 'đ';
    }

    let rowDiscount = document.getElementById('rowDiscount');
    if (discount > 0) {
        rowDiscount.style.display = 'flex';
        document.getElementById('txtDiscount').innerText = '-' + new Intl.NumberFormat('vi-VN').format(discount) + 'đ';
    } else {
        rowDiscount.style.display = 'none';
    }

    document.getElementById('txtFinalTotal').innerText = new Intl.NumberFormat('vi-VN').format(finalTotal) + 'đ';
}

// Chạy lần đầu
calculateTotal();
</script>

<?php include 'views/layout/footer.php'; ?>