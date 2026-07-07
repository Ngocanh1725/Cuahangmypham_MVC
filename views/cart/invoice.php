<?php 
$pageTitle = "Hóa Đơn #" . $order['id'] . " - Glow Cosmetics"; 
$extraCSS = "<style>
    .invoice-card { background: #fff; border-radius: 15px; padding: 40px; }
    .invoice-header { border-bottom: 2px dashed #eee; padding-bottom: 20px; margin-bottom: 20px; }
    .qr-box { background: #fdfaf7; border: 2px dashed #be185d; border-radius: 16px; padding: 20px; text-align: center; }
    .qr-image { width: 250px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 15px;}
    @media print {
        body { background: white !important; }
        nav, footer, .no-print { display: none !important; }
        .invoice-card { box-shadow: none !important; padding: 0; margin: 0; }
    }
</style>";
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 

// =========================================================================
// THIẾT LẬP THÔNG TIN TÀI KHOẢN NGÂN HÀNG CỦA BẠN TẠI ĐÂY
// =========================================================================
$MY_BANK_ID = "MB"; // Tên viết tắt ngân hàng (VD: MB, VCB, TCB, VPB, ACB...)
$MY_ACCOUNT_NO = "0987654321"; // Số tài khoản của bạn
$MY_ACCOUNT_NAME = "NGUYEN VAN A"; // Tên chủ tài khoản (Viết hoa không dấu)
// =========================================================================

$isQR = (isset($order['payment_method']) && $order['payment_method'] == 'Chuyển khoản (Mã QR)');
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="invoice-card shadow-lg border-0">
                
                <!-- Lời cảm ơn (Ẩn khi in) -->
                <div class="text-center mb-4 no-print">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h3 class="fw-bold text-success">Đặt hàng thành công!</h3>
                    <p class="text-muted">Cảm ơn bạn đã tin tưởng và mua sắm tại Glow Cosmetics.</p>
                </div>

                <!-- HIỂN THỊ KHU VỰC QUÉT MÃ QR NẾU KHÁCH CHỌN CHUYỂN KHOẢN -->
                <?php if($isQR): 
                    // Tạo nội dung chuyển khoản tự động
                    $transferMessage = "Thanh toan don hang ORD" . $order['id'];
                    $amount = $order['total_price'];
                    
                    // Link tạo QR code tự động từ VietQR
                    $qrUrl = "https://img.vietqr.io/image/{$MY_BANK_ID}-{$MY_ACCOUNT_NO}-compact2.png?amount={$amount}&addInfo=" . urlencode($transferMessage) . "&accountName=" . urlencode($MY_ACCOUNT_NAME);
                ?>
                <div class="row justify-content-center mb-5 no-print">
                    <div class="col-md-10">
                        <div class="qr-box">
                            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-qrcode text-primary me-2"></i>Quét mã QR để thanh toán</h5>
                            <img src="<?php echo $qrUrl; ?>" alt="QR Code Thanh Toán" class="qr-image">
                            
                            <div class="bg-white p-3 rounded-3 mt-2 text-start border shadow-sm mx-auto" style="max-width: 350px;">
                                <div class="mb-2 d-flex justify-content-between border-bottom pb-2">
                                    <span class="text-muted">Ngân hàng:</span> <strong><?php echo $MY_BANK_ID; ?></strong>
                                </div>
                                <div class="mb-2 d-flex justify-content-between border-bottom pb-2">
                                    <span class="text-muted">Chủ TK:</span> <strong><?php echo $MY_ACCOUNT_NAME; ?></strong>
                                </div>
                                <div class="mb-2 d-flex justify-content-between border-bottom pb-2">
                                    <span class="text-muted">Số TK:</span> <strong class="text-primary"><?php echo $MY_ACCOUNT_NO; ?></strong>
                                </div>
                                <div class="mb-2 d-flex justify-content-between border-bottom pb-2">
                                    <span class="text-muted">Số tiền:</span> <strong class="text-danger"><?php echo number_format($amount); ?>đ</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Nội dung:</span> <strong class="text-dark"><?php echo $transferMessage; ?></strong>
                                </div>
                            </div>
                            
                            <p class="text-danger small fw-bold mt-3 mb-0"><i class="fas fa-exclamation-circle me-1"></i> Đơn hàng sẽ được xử lý ngay khi chúng tôi nhận được thanh toán.</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Header Hóa Đơn -->
                <div class="invoice-header d-flex flex-column flex-sm-row justify-content-between align-items-center">
                    <div class="text-center text-sm-start mb-3 mb-sm-0">
                        <h2 class="fw-bold" style="color: var(--brand-dark, #be185d);"><i class="fas fa-spa me-2"></i>GLOW STORE</h2>
                        <p class="text-muted mb-0">Hóa Đơn Mua Hàng Điện Tử</p>
                    </div>
                    <div class="text-center text-sm-end">
                        <h5 class="fw-bold mb-1">Mã đơn: #ORD-<?php echo str_pad($order['id'], 4, '0', STR_PAD_LEFT); ?></h5>
                        <p class="text-muted mb-0">Ngày đặt: <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>
                    </div>
                </div>

                <!-- Thông tin khách hàng -->
                <div class="row mb-4 bg-light p-3 rounded-3 mx-0">
                    <div class="col-sm-6">
                        <?php if(isset($order['delivery_method']) && $order['delivery_method'] == 'pickup'): ?>
                            <h6 class="fw-bold text-muted text-uppercase mb-2"><i class="fas fa-store me-1"></i> Nhận tại cửa hàng</h6>
                        <?php else: ?>
                            <h6 class="fw-bold text-muted text-uppercase mb-2"><i class="fas fa-truck me-1"></i> Giao hàng đến</h6>
                        <?php endif; ?>
                        <p class="mb-1"><strong>Khách hàng:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                        <p class="mb-1"><strong>SĐT:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                        <p class="mb-0"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></p>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                        <h6 class="fw-bold text-muted text-uppercase mb-2">Phương thức thanh toán</h6>
                        <p class="mb-2 fw-bold text-primary">
                            <?php 
                                if(isset($order['payment_method']) && !empty($order['payment_method'])) {
                                    echo $order['payment_method'];
                                } else {
                                    echo "Thanh toán khi nhận hàng (COD)";
                                }
                            ?>
                        </p>
                        <!-- Nếu là QR thì đơn hàng sẽ chờ xác nhận thanh toán -->
                        <?php if($isQR && $order['status'] == 'Chờ xử lý'): ?>
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fas fa-clock me-1"></i> Chờ chuyển khoản</span>
                        <?php else: ?>
                            <span class="badge bg-secondary px-3 py-2 rounded-pill"><?php echo $order['status']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Bảng sản phẩm -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered border-light align-middle">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="py-3">#</th>
                                <th class="py-3">Sản phẩm</th>
                                <th class="text-center py-3">Đơn giá</th>
                                <th class="text-center py-3">Số lượng</th>
                                <th class="text-end py-3">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stt = 1;
                            $subtotal = 0;
                            foreach ($orderDetails as $item) {
                                $thanh_tien = $item['price'] * $item['quantity'];
                                $subtotal += $thanh_tien;
                                echo "
                                <tr>
                                    <td class='text-muted fw-bold'>$stt</td>
                                    <td class='fw-bold text-dark'>{$item['name']}</td>
                                    <td class='text-center'>".number_format($item['price'])."đ</td>
                                    <td class='text-center fw-bold'>{$item['quantity']}</td>
                                    <td class='text-end text-dark fw-bold'>".number_format($thanh_tien)."đ</td>
                                </tr>
                                ";
                                $stt++;
                            }
                            ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end text-muted py-2">Tạm tính:</td>
                                <td class="text-end fw-bold"><?php echo number_format($subtotal); ?>đ</td>
                            </tr>
                            <?php if(isset($order['shipping_fee'])): ?>
                            <tr>
                                <td colspan="4" class="text-end text-muted py-2">Phí giao hàng:</td>
                                <td class="text-end fw-bold"><?php echo $order['shipping_fee'] > 0 ? number_format($order['shipping_fee']).'đ' : 'Miễn phí'; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(isset($order['discount_amount']) && $order['discount_amount'] > 0): ?>
                            <tr>
                                <td colspan="4" class="text-end text-success py-2">Giảm giá/Điểm:</td>
                                <td class="text-end fw-bold text-success">-<?php echo number_format($order['discount_amount']); ?>đ</td>
                            </tr>
                            <?php endif; ?>
                            <?php if(isset($order['vat_amount']) && $order['vat_amount'] > 0): ?>
                            <tr>
                                <td colspan="4" class="text-end text-muted py-2">Thuế VAT:</td>
                                <td class="text-end fw-bold"><?php echo number_format($order['vat_amount']); ?>đ</td>
                            </tr>
                            <?php endif; ?>
                            <tr class="border-top">
                                <td colspan="4" class="text-end fw-bold py-3 fs-5">Tổng Thanh Toán:</td>
                                <td class="text-end fs-4 fw-bold" style="color: var(--brand-dark, #be185d);"><?php echo number_format($order['total_price']); ?>đ</td>
                            </tr>
                            <?php if(isset($order['points_earned']) && $order['points_earned'] > 0): ?>
                            <tr>
                                <td colspan="5" class="text-end text-warning py-2 small fw-bold">
                                    <i class="fas fa-coins me-1"></i> Nhận được <?php echo number_format($order['points_earned']); ?> điểm tích lũy từ đơn hàng này!
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tfoot>
                    </table>
                </div>

                <!-- Nút điều hướng -->
                <div class="d-flex justify-content-center gap-3 mt-5 no-print border-top pt-4">
                    <a href="index.php" class="btn btn-light border px-4 rounded-pill fw-bold"><i class="fas fa-arrow-left me-2"></i>Về trang chủ</a>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?controller=user&action=orders" class="btn btn-outline-primary px-4 rounded-pill fw-bold"><i class="fas fa-list me-2"></i>Đơn mua của tôi</a>
                    <?php endif; ?>
                    
                    <button onclick="window.print()" class="btn btn-dark px-4 rounded-pill fw-bold"><i class="fas fa-print me-2"></i>In hóa đơn</button>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>