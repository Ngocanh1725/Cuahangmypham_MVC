<?php 
$pageTitle = "Thanh toán - Glow Cosmetics"; 
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4 text-center" style="color: var(--brand-dark, #be185d);">Tiến Hành Thanh Toán</h2>

    <?php if(!empty($message)) echo $message; ?>

    <div class="row">
        <!-- Form thông tin giao hàng -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4"><i class="fas fa-map-marker-alt text-danger me-2"></i>Thông tin giao hàng</h4>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và Tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" value="<?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : ''; ?>" required placeholder="Ví dụ: Nguyễn Văn A">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="customer_phone" class="form-control" required placeholder="09xxxxxxx">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Địa chỉ giao hàng chi tiết <span class="text-danger">*</span></label>
                            <textarea name="customer_address" class="form-control" rows="3" required placeholder="Số nhà, Tên đường, Phường/Xã, Quận/Huyện, Tỉnh/Thành phố"></textarea>
                        </div>

                        <h5 class="fw-bold mb-3 mt-4"><i class="fas fa-credit-card text-success me-2"></i>Phương thức thanh toán</h5>
                        <div class="card mb-2 border shadow-sm">
                            <div class="card-body p-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="pay_cod" value="Thanh toán khi nhận hàng (COD)" checked>
                                    <label class="form-check-label fw-bold d-flex align-items-center" for="pay_cod">
                                        <i class="fas fa-money-bill-wave text-success me-2 fs-5"></i> Thanh toán khi nhận hàng (COD)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4 border shadow-sm">
                            <div class="card-body p-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="pay_bank" value="Chuyển khoản ngân hàng">
                                    <label class="form-check-label fw-bold d-flex align-items-center" for="pay_bank">
                                        <i class="fas fa-university text-primary me-2 fs-5"></i> Chuyển khoản ngân hàng
                                    </label>
                                </div>
                                <div class="small text-muted mt-2 ms-4">
                                    Thực hiện chuyển khoản vào tài khoản ngân hàng của Glow Store. Vui lòng ghi rõ Mã Đơn Hàng trong nội dung chuyển khoản.
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn text-white w-100 py-3 fw-bold fs-5 rounded-pill shadow-sm" style="background-color: var(--brand-main, #db2777);">
                            <i class="fas fa-check-circle me-2"></i> Xác Nhận Đặt Hàng
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tóm tắt đơn hàng -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                <div class="card-body p-4 bg-white rounded-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-3">Tóm tắt đơn hàng</h5>
                    <ul class="list-group list-group-flush mb-3">
                        <?php foreach ($cartItems as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="d-flex align-items-center">
                                    <div class="ms-2">
                                        <h6 class="mb-0 text-dark fw-bold" style="font-size: 0.95rem;"><?php echo htmlspecialchars($item['name']); ?></h6>
                                        <small class="text-muted">SL: <?php echo $item['qty']; ?></small>
                                    </div>
                                </div>
                                <span class="text-danger fw-bold"><?php echo number_format($item['subtotal']); ?>đ</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="d-flex justify-content-between mb-2 mt-3">
                        <span class="text-muted">Phí giao hàng</span>
                        <span class="text-success fw-bold">Miễn phí</span>
                    </div>
                    <hr class="my-3 text-muted">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold fs-5">Tổng thanh toán</span>
                        <span class="fw-bold fs-4" style="color: var(--brand-dark, #be185d);"><?php echo number_format($totalPrice); ?>đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>