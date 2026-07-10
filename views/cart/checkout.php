<?php 
$pageTitle = "Thanh toán - Glow Beauty"; 
include 'views/layout/header.php'; 
?>

<!-- ============================
     CHECKOUT PAGE STYLES
     ============================ -->
<style>
    /* ---- Layout ---- */
    .checkout-page {
        background: linear-gradient(135deg, #fdfbf9 0%, #fce7eb33 100%);
        min-height: 100vh;
        padding: 40px 0 80px;
    }
    .checkout-page-title {
        font-family: var(--font-serif, 'Playfair Display', serif);
        font-weight: 800;
        font-size: 2rem;
        color: var(--rhode-pink-accent, #d85c7b);
        text-align: center;
        margin-bottom: 8px;
    }
    .checkout-page-subtitle {
        text-align: center;
        color: var(--text-light, #8c8181);
        font-size: 0.95rem;
        margin-bottom: 32px;
    }

    /* ---- Card chung ---- */
    .ck-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(216, 92, 123, 0.06);
        background: #fff;
        margin-bottom: 24px;
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }
    .ck-card:hover {
        box-shadow: 0 8px 32px rgba(216, 92, 123, 0.1);
    }
    .ck-card-header {
        background: linear-gradient(135deg, #fce7eb 0%, #fff 100%);
        padding: 18px 24px;
        border-bottom: 1px solid #fce7eb;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .ck-card-header .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--rhode-pink-accent, #d85c7b);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    .ck-card-header h5 {
        font-family: var(--font-serif, 'Playfair Display', serif);
        font-weight: 700;
        margin: 0;
        color: var(--text-main, #4a4040);
        font-size: 1.1rem;
    }
    .ck-card-body {
        padding: 24px;
    }

    /* ---- Form Inputs ---- */
    .ck-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-light, #8c8181);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: block;
    }
    .ck-input, .ck-select, .ck-textarea {
        border-radius: 12px;
        padding: 12px 16px;
        border: 1.5px solid #e8e0e0;
        background: #fdfbf9;
        font-size: 0.95rem;
        color: var(--text-main, #4a4040);
        transition: all 0.25s ease;
        width: 100%;
        font-family: var(--font-sans, 'Poppins', sans-serif);
    }
    .ck-input:focus, .ck-select:focus, .ck-textarea:focus {
        border-color: var(--rhode-pink-accent, #d85c7b);
        box-shadow: 0 0 0 3px rgba(216, 92, 123, 0.1);
        background: #fff;
        outline: none;
    }
    .ck-textarea {
        resize: vertical;
        min-height: 80px;
    }

    /* ---- Delivery Options ---- */
    .delivery-option {
        border: 2px solid #e8e0e0;
        border-radius: 16px;
        padding: 18px 16px;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .delivery-option::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: var(--rhode-pink-light, #fce7eb);
        opacity: 0;
        transition: opacity 0.25s ease;
        z-index: 0;
    }
    .delivery-option:hover {
        border-color: #f5b8c4;
    }
    .delivery-option:hover::before {
        opacity: 0.3;
    }
    .delivery-option.active {
        border-color: var(--rhode-pink-accent, #d85c7b);
        background: linear-gradient(135deg, #fce7eb 0%, #fff5f7 100%);
    }
    .delivery-option.active::before { opacity: 0; }
    .delivery-option input[type="radio"] { display: none; }
    .delivery-option .opt-content {
        position: relative;
        z-index: 1;
    }
    .delivery-option .opt-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .delivery-option.active .opt-icon {
        box-shadow: 0 4px 12px rgba(216, 92, 123, 0.2);
    }
    .delivery-option .check-mark {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 2px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
        z-index: 1;
    }
    .delivery-option.active .check-mark {
        background: var(--rhode-pink-accent, #d85c7b);
        border-color: var(--rhode-pink-accent, #d85c7b);
        color: #fff;
    }

    /* ---- Store Pickup Info ---- */
    .store-pickup-info {
        background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
        border-radius: 12px;
        padding: 14px 18px;
        border-left: 4px solid #4caf50;
    }

    /* ---- VAT Checkbox ---- */
    .vat-toggle {
        background: #fdfbf9;
        border: 1.5px solid #e8e0e0;
        border-radius: 14px;
        padding: 16px 20px;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .vat-toggle:hover {
        border-color: #f5b8c4;
        background: #fff5f7;
    }
    .vat-toggle.active {
        border-color: var(--rhode-pink-accent, #d85c7b);
        background: #fff5f7;
    }
    .vat-form {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease, opacity 0.3s ease, padding 0.3s ease;
        opacity: 0;
        padding: 0 0;
    }
    .vat-form.show {
        max-height: 400px;
        opacity: 1;
        padding: 20px 0 0;
    }

    /* ---- Payment Methods ---- */
    .pay-option {
        border: 2px solid #e8e0e0;
        border-radius: 14px;
        padding: 16px 18px;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .pay-option:hover {
        border-color: #f5b8c4;
        background: #fffafa;
    }
    .pay-option.active {
        border-color: var(--rhode-pink-accent, #d85c7b);
        background: linear-gradient(135deg, #fce7eb 0%, #fff5f7 100%);
    }
    .pay-option input[type="radio"] { display: none; }
    .pay-option .pay-logo {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .pay-option .pay-check {
        margin-left: auto;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
        flex-shrink: 0;
    }
    .pay-option.active .pay-check {
        background: var(--rhode-pink-accent, #d85c7b);
        border-color: var(--rhode-pink-accent, #d85c7b);
        color: #fff;
    }

    /* ---- Bank Transfer Details ---- */
    .bank-info-box {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease, opacity 0.3s ease;
        opacity: 0;
    }
    .bank-info-box.show {
        max-height: 300px;
        opacity: 1;
    }
    .bank-detail-card {
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        border-radius: 14px;
        padding: 18px 20px;
        margin-top: 14px;
        border-left: 4px solid #42a5f5;
    }

    /* ---- Right Column: Order Summary ---- */
    .order-summary-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 40px rgba(216, 92, 123, 0.08);
        background: #fff;
        overflow: hidden;
        position: sticky;
        top: 100px;
    }
    .order-summary-header {
        background: linear-gradient(135deg, var(--rhode-pink-accent, #d85c7b), #e8849b);
        padding: 20px 24px;
        color: #fff;
    }
    .order-summary-header h5 {
        font-family: var(--font-serif, 'Playfair Display', serif);
        font-weight: 700;
        margin: 0;
        color: #fff;
        font-size: 1.15rem;
    }
    .order-summary-body {
        padding: 24px;
    }

    /* ---- Cart Items in Summary ---- */
    .summary-product {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f5f0f0;
        gap: 12px;
    }
    .summary-product:last-child { border-bottom: none; }
    .summary-product-img {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #fce7eb;
    }
    .summary-product-name {
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--text-main, #4a4040);
        line-height: 1.3;
        margin-bottom: 2px;
    }
    .summary-product-qty {
        font-size: 0.8rem;
        color: var(--text-light, #8c8181);
    }
    .summary-product-price {
        font-weight: 700;
        color: var(--rhode-pink-accent, #d85c7b);
        font-size: 0.9rem;
        white-space: nowrap;
        margin-left: auto;
        flex-shrink: 0;
    }

    /* ---- Coupon ---- */
    .coupon-wrap {
        display: flex;
        gap: 0;
        margin-bottom: 6px;
    }
    .coupon-wrap .ck-input {
        border-radius: 12px 0 0 12px;
        border-right: none;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
    }
    .coupon-wrap .coupon-btn {
        border-radius: 0 12px 12px 0;
        padding: 0 20px;
        background: var(--rhode-pink-accent, #d85c7b);
        color: #fff;
        border: 1.5px solid var(--rhode-pink-accent, #d85c7b);
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.25s ease;
        white-space: nowrap;
        font-family: var(--font-sans, 'Poppins', sans-serif);
    }
    .coupon-wrap .coupon-btn:hover {
        background: var(--rhode-pink-hover, #c44766);
        border-color: var(--rhode-pink-hover, #c44766);
    }
    #couponMessage {
        min-height: 20px;
    }

    /* ---- Tier Badge ---- */
    .tier-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* ---- Pricing Summary ---- */
    .price-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        font-size: 0.92rem;
        color: var(--text-main, #4a4040);
    }
    .price-line .label { color: var(--text-light, #8c8181); }
    .price-line .value { font-weight: 600; }
    .price-line.discount { color: #2e7d32; }
    .price-line.discount .value { color: #2e7d32; }
    .price-divider {
        border: none;
        border-top: 2px dashed #f0e0e0;
        margin: 12px 0;
    }
    .price-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0 4px;
    }
    .price-total .label {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-main, #4a4040);
    }
    .price-total .value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--rhode-pink-accent, #d85c7b);
        font-family: var(--font-serif, 'Playfair Display', serif);
    }

    /* ---- Submit Button ---- */
    .btn-checkout {
        width: 100%;
        padding: 16px 24px;
        border: none;
        border-radius: 50px;
        background: linear-gradient(135deg, var(--rhode-pink-accent, #d85c7b), #e8849b);
        color: #fff;
        font-size: 1.1rem;
        font-weight: 700;
        font-family: var(--font-sans, 'Poppins', sans-serif);
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 6px 20px rgba(216, 92, 123, 0.25);
        margin-top: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(216, 92, 123, 0.35);
        background: linear-gradient(135deg, var(--rhode-pink-hover, #c44766), var(--rhode-pink-accent, #d85c7b));
    }
    .btn-checkout:active {
        transform: translateY(0);
    }

    /* ---- Secure Badge ---- */
    .secure-badge {
        text-align: center;
        margin-top: 14px;
        color: var(--text-light, #8c8181);
        font-size: 0.8rem;
    }

    /* ---- Responsive ---- */
    @media (max-width: 991px) {
        .checkout-page { padding: 20px 0 60px; }
        .order-summary-card { position: static; margin-top: 24px; }
    }
    @media (max-width: 575px) {
        .ck-card-body { padding: 16px; }
        .order-summary-body { padding: 16px; }
    }
</style>

<div class="checkout-page">
    <div class="container" style="max-width: 1140px;">
        <h2 class="checkout-page-title">Hoàn Tất Đơn Hàng</h2>
        <p class="checkout-page-subtitle">Kiểm tra thông tin và xác nhận đơn hàng của bạn</p>

        <?php if(!empty($message)) echo $message; ?>

        <form method="POST" id="checkoutForm">
            <div class="row g-4">

                <!-- ============================================ -->
                <!-- CỘT TRÁI: THÔNG TIN KHÁCH HÀNG & TÙY CHỌN  -->
                <!-- ============================================ -->
                <div class="col-lg-7">

                    <!-- CARD 1: Thông tin người mua -->
                    <div class="ck-card">
                        <div class="ck-card-header">
                            <span class="step-number">1</span>
                            <h5><i class="fas fa-user me-2" style="color:var(--rhode-pink-accent)"></i>Thông Tin Người Mua</h5>
                        </div>
                        <div class="ck-card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="ck-label">Họ và Tên <span style="color:#d85c7b">*</span></label>
                                    <input type="text" name="customer_name" class="ck-input" 
                                           value="<?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>" 
                                           required placeholder="Nguyễn Văn A">
                                </div>
                                <div class="col-md-6">
                                    <label class="ck-label">Số Điện Thoại <span style="color:#d85c7b">*</span></label>
                                    <input type="tel" name="customer_phone" class="ck-input" 
                                           value="<?= htmlspecialchars($userData['phone'] ?? '') ?>" 
                                           required placeholder="0912 345 678">
                                </div>
                                <div class="col-md-12">
                                    <label class="ck-label">Email</label>
                                    <input type="email" name="customer_email" class="ck-input" 
                                           value="<?= htmlspecialchars($userData['email'] ?? $_SESSION['email'] ?? '') ?>" 
                                           placeholder="email@example.com">
                                </div>
                                <!-- Địa chỉ chi tiết -->
                                <div class="col-md-6">
                                    <label class="ck-label">Tỉnh / Thành phố <span style="color:#d85c7b">*</span></label>
                                    <input type="text" name="customer_city" class="ck-input" required placeholder="VD: Hà Nội">
                                </div>
                                <div class="col-md-6">
                                    <label class="ck-label">Quận / Huyện <span style="color:#d85c7b">*</span></label>
                                    <input type="text" name="customer_district" class="ck-input" required placeholder="VD: Cầu Giấy">
                                </div>
                                <div class="col-md-6">
                                    <label class="ck-label">Phường / Xã <span style="color:#d85c7b">*</span></label>
                                    <input type="text" name="customer_ward" class="ck-input" required placeholder="VD: Dịch Vọng">
                                </div>
                                <div class="col-md-6">
                                    <label class="ck-label">Số nhà, Đường <span style="color:#d85c7b">*</span></label>
                                    <input type="text" name="customer_street" class="ck-input" required 
                                           placeholder="VD: Số 1, Đường Xuân Thủy">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 2: Phương thức vận chuyển -->
                    <div class="ck-card">
                        <div class="ck-card-header">
                            <span class="step-number">2</span>
                            <h5><i class="fas fa-truck me-2" style="color:var(--rhode-pink-accent)"></i>Phương Thức Nhận Hàng</h5>
                        </div>
                        <div class="ck-card-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="delivery-option w-100 active" id="lblShipping" onclick="toggleDelivery('shipping')">
                                        <input type="radio" name="delivery_method" value="shipping" checked>
                                        <div class="opt-content d-flex align-items-center gap-3">
                                            <div class="opt-icon" style="background:#fce7eb; color:#d85c7b;">
                                                <i class="fas fa-motorcycle"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold" style="font-size:0.95rem">Giao tận nơi</div>
                                                <div style="font-size:0.8rem; color:var(--text-light)">2-5 ngày làm việc</div>
                                            </div>
                                        </div>
                                        <span class="check-mark"><i class="fas fa-check" style="font-size:0.65rem"></i></span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="delivery-option w-100" id="lblPickup" onclick="toggleDelivery('pickup')">
                                        <input type="radio" name="delivery_method" value="pickup">
                                        <div class="opt-content d-flex align-items-center gap-3">
                                            <div class="opt-icon" style="background:#e8f5e9; color:#4caf50;">
                                                <i class="fas fa-store"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold" style="font-size:0.95rem">Nhận tại cửa hàng</div>
                                                <div style="font-size:0.8rem; color:var(--text-light)">Miễn phí vận chuyển</div>
                                            </div>
                                        </div>
                                        <span class="check-mark"><i class="fas fa-check" style="font-size:0.65rem"></i></span>
                                    </label>
                                </div>
                            </div>

                            <!-- Dropdown chọn cửa hàng (ẩn mặc định) -->
                            <div id="pickupSection" style="display:none;">
                                <label class="ck-label">Chọn cửa hàng nhận hàng</label>
                                <select name="store_id" id="selectStore" class="ck-select">
                                    <option value="">-- Vui lòng chọn chi nhánh --</option>
                                    <?php if(!empty($stores)) foreach($stores as $s): ?>
                                        <option value="<?= $s['id'] ?>">
                                            <?= htmlspecialchars($s['name']) ?> — <?= htmlspecialchars($s['address']) ?>
                                            (<?= htmlspecialchars($s['open_hours'] ?? '08:00-21:30') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <!-- Div hiển thị lỗi tồn kho tại cơ sở -->
                                <div id="storeStockError" class="alert alert-danger mt-3" style="display:none; font-size: 0.9rem; padding: 10px 15px;"></div>

                                <div class="store-pickup-info mt-3">
                                    <i class="fas fa-info-circle text-success me-2"></i>
                                    <small>Bạn sẽ <strong>không mất phí vận chuyển</strong> khi nhận tại cửa hàng. Vui lòng mang theo CCCD khi đến nhận.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 3: Hóa đơn VAT -->
                    <div class="ck-card">
                        <div class="ck-card-header">
                            <span class="step-number">3</span>
                            <h5><i class="fas fa-file-invoice me-2" style="color:var(--rhode-pink-accent)"></i>Hóa Đơn VAT</h5>
                        </div>
                        <div class="ck-card-body">
                            <label class="vat-toggle d-flex align-items-center gap-3 mb-0" id="vatToggleLabel">
                                <input type="checkbox" name="vat_requested" id="vatCheckbox" value="1" 
                                       style="width:20px; height:20px; accent-color:#d85c7b; cursor:pointer;" 
                                       onchange="toggleVatForm()">
                                <div>
                                    <div class="fw-bold" style="font-size:0.95rem">Tôi muốn xuất hóa đơn VAT</div>
                                    <div style="font-size:0.8rem; color:var(--text-light)">Hóa đơn sẽ được gửi qua email trong 5-7 ngày làm việc</div>
                                </div>
                            </label>

                            <div class="vat-form" id="vatForm">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="ck-label">Tên Công Ty <span style="color:#d85c7b">*</span></label>
                                        <input type="text" name="vat_company_name" class="ck-input" placeholder="Công ty TNHH ABC">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="ck-label">Mã Số Thuế <span style="color:#d85c7b">*</span></label>
                                        <input type="text" name="vat_tax_code" class="ck-input" placeholder="0123456789">
                                    </div>
                                    <div class="col-12">
                                        <label class="ck-label">Địa Chỉ Công Ty <span style="color:#d85c7b">*</span></label>
                                        <input type="text" name="vat_company_address" class="ck-input" placeholder="Số 1, Đường ABC, Quận XYZ...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 4: Phương thức thanh toán -->
                    <div class="ck-card">
                        <div class="ck-card-header">
                            <span class="step-number">4</span>
                            <h5><i class="fas fa-wallet me-2" style="color:var(--rhode-pink-accent)"></i>Phương Thức Thanh Toán</h5>
                        </div>
                        <div class="ck-card-body">
                            <div class="d-flex flex-column gap-3">
                                <!-- COD -->
                                <label class="pay-option active" id="payOptCOD" onclick="selectPayment('COD')">
                                    <input type="radio" name="payment_method" value="COD" checked>
                                    <div class="pay-logo" style="background:#e8f5e9; color:#4caf50;">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="font-size:0.95rem">Thanh toán khi nhận hàng (COD)</div>
                                        <div style="font-size:0.78rem; color:var(--text-light)">Trả tiền mặt cho shipper</div>
                                    </div>
                                    <span class="pay-check"><i class="fas fa-check" style="font-size:0.6rem"></i></span>
                                </label>

                                <!-- Bank Transfer -->
                                <label class="pay-option" id="payOptBank" onclick="selectPayment('bank_transfer')">
                                    <input type="radio" name="payment_method" value="bank_transfer">
                                    <div class="pay-logo" style="background:#e3f2fd; color:#1976d2;">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="font-size:0.95rem">Chuyển khoản Ngân hàng</div>
                                        <div style="font-size:0.78rem; color:var(--text-light)">Quét mã QR hoặc chuyển khoản thủ công</div>
                                    </div>
                                    <span class="pay-check"><i class="fas fa-check" style="font-size:0.6rem"></i></span>
                                </label>
                                <!-- Bank Info Box -->
                                <div class="bank-info-box" id="bankInfoBox">
                                    <div class="bank-detail-card">
                                        <div class="fw-bold mb-2"><i class="fas fa-info-circle text-primary me-2"></i>Thông tin chuyển khoản</div>
                                        <table style="font-size:0.88rem; width:100%;">
                                            <tr><td style="color:var(--text-light); padding:3px 12px 3px 0;">Ngân hàng:</td><td class="fw-bold"><?= htmlspecialchars($settings['bank_name']['setting_value'] ?? 'Vietcombank') ?></td></tr>
                                            <tr><td style="color:var(--text-light); padding:3px 12px 3px 0;">Số TK:</td><td class="fw-bold"><?= htmlspecialchars($settings['bank_account_number']['setting_value'] ?? '') ?></td></tr>
                                            <tr><td style="color:var(--text-light); padding:3px 12px 3px 0;">Chủ TK:</td><td class="fw-bold"><?= htmlspecialchars($settings['bank_account_name']['setting_value'] ?? '') ?></td></tr>
                                            <tr><td style="color:var(--text-light); padding:3px 12px 3px 0;">Chi nhánh:</td><td class="fw-bold"><?= htmlspecialchars($settings['bank_branch']['setting_value'] ?? '') ?></td></tr>
                                        </table>
                                        <div class="mt-2" style="font-size:0.8rem; color:var(--text-light);">Nội dung CK: <strong>GLOW [SĐT]</strong></div>
                                    </div>
                                </div>

                                <!-- ZaloPay -->
                                <label class="pay-option" id="payOptZalo" onclick="selectPayment('zalopay')">
                                    <input type="radio" name="payment_method" value="zalopay">
                                    <div class="pay-logo" style="background:#e3f2fd; color:#0068ff;">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="font-size:0.95rem">ZaloPay</div>
                                        <div style="font-size:0.78rem; color:var(--text-light)">Thanh toán qua ví ZaloPay</div>
                                    </div>
                                    <span class="pay-check"><i class="fas fa-check" style="font-size:0.6rem"></i></span>
                                </label>

                                <!-- MoMo -->
                                <label class="pay-option" id="payOptMomo" onclick="selectPayment('momo')">
                                    <input type="radio" name="payment_method" value="momo">
                                    <div class="pay-logo" style="background:#fce4ec; color:#ae2070;">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="font-size:0.95rem">MoMo</div>
                                        <div style="font-size:0.78rem; color:var(--text-light)">Thanh toán qua ví MoMo</div>
                                    </div>
                                    <span class="pay-check"><i class="fas fa-check" style="font-size:0.6rem"></i></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 5: Ghi chú đơn hàng -->
                    <div class="ck-card">
                        <div class="ck-card-header">
                            <span class="step-number">5</span>
                            <h5><i class="fas fa-comment-dots me-2" style="color:var(--rhode-pink-accent)"></i>Ghi Chú Đơn Hàng</h5>
                        </div>
                        <div class="ck-card-body">
                            <textarea name="note" class="ck-textarea" rows="3" placeholder="Ghi chú cho người bán: Thời gian nhận hàng, yêu cầu đặc biệt..."></textarea>
                        </div>
                    </div>

                </div>

                <!-- ============================================ -->
                <!-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG                -->
                <!-- ============================================ -->
                <div class="col-lg-5">
                    <div class="order-summary-card">
                        <div class="order-summary-header">
                            <h5><i class="fas fa-receipt me-2"></i>Tóm Tắt Đơn Hàng</h5>
                        </div>
                        <div class="order-summary-body">

                            <!-- Danh sách sản phẩm -->
                            <div style="max-height: 280px; overflow-y: auto; margin-bottom: 16px;">
                                <?php foreach ($cartItems as $item): ?>
                                    <div class="summary-product">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="summary-product-img">
                                        <div style="min-width:0; flex:1;">
                                            <div class="summary-product-name"><?= htmlspecialchars($item['name']) ?></div>
                                            <div class="summary-product-qty">x<?= $item['qty'] ?></div>
                                        </div>
                                        <div class="summary-product-price"><?= number_format($item['subtotal']) ?>đ</div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <hr class="price-divider">

                            <!-- Mã giảm giá (Coupon) -->
                            <div style="margin-bottom: 16px;">
                                <label class="ck-label">Mã giảm giá</label>
                                <div class="coupon-wrap">
                                    <input type="text" id="couponCode" class="ck-input" placeholder="Nhập mã ưu đãi">
                                    <button type="button" class="coupon-btn" onclick="applyCoupon()">
                                        <i class="fas fa-tag me-1"></i>ÁP DỤNG
                                    </button>
                                </div>
                                <div id="couponMessage" style="font-size:0.82rem; margin-top:6px;"></div>
                            </div>

                            <!-- Thông tin hạng thành viên -->
                            <?php if($userData && !empty($userData['tier_name'])): ?>
                                <div style="background:#fdfbf9; border:1.5px solid #fce7eb; border-radius:12px; padding:12px 16px; margin-bottom:16px;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="tier-badge" style="background:<?= htmlspecialchars($userData['color_code'] ?? '#cd7f32') ?>22; color:<?= htmlspecialchars($userData['color_code'] ?? '#cd7f32') ?>;">
                                                <i class="<?= htmlspecialchars($userData['icon'] ?? 'fas fa-medal') ?>"></i>
                                                <?= htmlspecialchars($userData['tier_name']) ?>
                                            </span>
                                        </div>
                                        <div style="font-size:0.85rem; font-weight:600; color:#2e7d32;">
                                            Giảm <?= $userData['discount_percent'] ?>%
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <input type="hidden" name="coupon_code" id="hiddenCouponCode" value="">

                            <!-- Khối tính tiền -->
                            <div class="price-line">
                                <span class="label">Tạm tính (<?= count($cartItems) ?> sp)</span>
                                <span class="value" id="txtSubtotal"><?= number_format($totalPrice) ?>đ</span>
                            </div>

                            <div class="price-line discount" id="rowMemberDiscount" style="display:none;">
                                <span class="label"><i class="fas fa-star me-1"></i>Giảm hạng thành viên</span>
                                <span class="value" id="txtMemberDiscount">-0đ</span>
                            </div>

                            <div class="price-line discount" id="rowCouponDiscount" style="display:none;">
                                <span class="label"><i class="fas fa-tag me-1"></i>Mã khuyến mãi</span>
                                <span class="value" id="txtCouponDiscount">-0đ</span>
                            </div>

                            <div class="price-line">
                                <span class="label"><i class="fas fa-truck me-1"></i>Phí vận chuyển</span>
                                <span class="value" id="txtShipping">---</span>
                            </div>

                            <div class="price-line" id="rowVat" style="display:none;">
                                <span class="label"><i class="fas fa-percent me-1"></i>Thuế VAT (<span id="txtVatPercent">0</span>%)</span>
                                <span class="value" id="txtVat">0đ</span>
                            </div>

                            <hr class="price-divider">

                            <div class="price-total">
                                <span class="label">TỔNG CỘNG</span>
                                <span class="value" id="txtFinalTotal"><?= number_format($totalPrice) ?>đ</span>
                            </div>

                            <!-- Nút đặt hàng -->
                            <button type="submit" class="btn-checkout" id="btnSubmitOrder">
                                <i class="fas fa-lock"></i>
                                HOÀN TẤT ĐẶT HÀNG
                            </button>

                            <div class="secure-badge">
                                <i class="fas fa-shield-alt me-1"></i>
                                Thông tin được bảo mật và mã hóa an toàn
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- Nạp script xử lý Checkout (AJAX → Backend) -->
<script src="assets/js/checkout.js"></script>

<?php include 'views/layout/footer.php'; ?>