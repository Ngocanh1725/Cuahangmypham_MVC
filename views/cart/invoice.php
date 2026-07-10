<?php 
$pageTitle = "Hóa Đơn #" . $order['id'] . " - Glow Cosmetics"; 
$extraCSS = "<style>
    .invoice-card { background: #fff; border-radius: 15px; padding: 40px; }
    .invoice-header { border-bottom: 2px dashed #eee; padding-bottom: 20px; margin-bottom: 20px; }
    .qr-box { background: #fdfaf7; border: 2px dashed #be185d; border-radius: 16px; padding: 20px; text-align: center; }
    .qr-image { width: 250px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 15px;}
    
    /* Timeline CSS */
    .order-track { margin-top: 20px; margin-bottom: 30px; padding-top: 20px; border-top: 1px solid #eee; }
    .track { position: relative; background-color: #ddd; height: 7px; display: flex; margin-bottom: 60px; margin-top: 50px; border-radius: 3px; }
    .track .step { flex-grow: 1; width: 25%; margin-top: -18px; text-align: center; position: relative; }
    .track .step::before { height: 7px; position: absolute; content: \"\"; width: 100%; left: 0; top: 18px; }
    .track .step.active:before { background: #be185d; }
    .track .step.active .icon { background: #be185d; color: #fff; }
    .track .icon { display: inline-block; width: 40px; height: 40px; line-height: 40px; position: relative; border-radius: 100%; background: #ddd; color: #fff; z-index: 10; font-size: 18px; }
    .track .step.active .text { font-weight: 600; color: #000; }
    .track .text { display: block; margin-top: 7px; font-size: 0.85rem; color: #777; }
    .track .step.cancel::before { background: #dc3545; }
    .track .step.cancel .icon { background: #dc3545; color: #fff; }
    .track .step.cancel .text { font-weight: 600; color: #dc3545; }

    @media print {
        body { background: white !important; }
        nav, header, footer, .rhode-announcement-bar, .rhode-header-wrapper, .no-print { display: none !important; }
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

$isQR = (isset($order['payment_method']) && ($order['payment_method'] == 'Chuyển khoản (Mã QR)' || $order['payment_method'] == 'bank_transfer'));
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
                                $pm = $order['payment_method'] ?? '';
                                if ($pm == 'bank_transfer' || $pm == 'Chuyển khoản (Mã QR)') echo "Chuyển khoản Ngân hàng (QR)";
                                elseif ($pm == 'zalopay') echo "Thanh toán qua ZaloPay";
                                elseif ($pm == 'momo') echo "Thanh toán qua MoMo";
                                else echo "Thanh toán khi nhận hàng (COD)";
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

                <!-- TIẾN TRÌNH THEO DÕI ĐƠN HÀNG (TIMELINE) -->
                <div class="order-track no-print">
                    <h6 class="fw-bold mb-4 text-center">Tiến trình đơn hàng</h6>
                    <?php 
                    $status = $order['status'];
                    $step1 = $step2 = $step3 = $step4 = "";
                    $isCanceled = ($status == 'Đã hủy');

                    if ($isCanceled) {
                        $step1 = "active cancel"; // Đánh dấu lỗi
                    } else {
                        // Logic trạng thái
                        if ($status == 'Chờ xử lý' || $status == 'Đang giao' || $status == 'Hoàn thành') {
                            $step1 = "active";
                        }
                        // Giả lập trạng thái Chuẩn bị hàng nếu đã duyệt (chuyển sang đang giao hoặc hoàn thành)
                        if ($status == 'Đang giao' || $status == 'Hoàn thành') {
                            $step2 = "active";
                        }
                        if ($status == 'Đang giao' || $status == 'Hoàn thành') {
                            $step3 = "active";
                        }
                        if ($status == 'Hoàn thành') {
                            $step4 = "active";
                        }
                    }
                    ?>

                    <?php if ($isCanceled): ?>
                        <div class="track">
                            <div class="step <?php echo $step1; ?>">
                                <span class="icon"><i class="fas fa-times"></i></span>
                                <span class="text">Đơn đã hủy</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="track">
                            <div class="step <?php echo $step1; ?>">
                                <span class="icon"><i class="fas fa-clipboard-check"></i></span>
                                <span class="text">Xác nhận đơn</span>
                            </div>
                            <div class="step <?php echo $step2; ?>">
                                <span class="icon"><i class="fas fa-box-open"></i></span>
                                <span class="text">Chuẩn bị hàng</span>
                            </div>
                            <div class="step <?php echo $step3; ?>">
                                <span class="icon"><i class="fas fa-truck"></i></span>
                                <span class="text">Giao cho ship</span>
                            </div>
                            <div class="step <?php echo $step4; ?>">
                                <span class="icon"><i class="fas fa-box"></i></span>
                                <span class="text">Giao thành công</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- HIỂN THỊ THÔNG TIN XUẤT HÓA ĐƠN VAT -->
                <?php if (isset($order['vat_requested']) && $order['vat_requested'] == 1): ?>
                <div class="row mb-4 bg-light p-3 rounded-3 mx-0 border-start border-4 border-info">
                    <div class="col-12">
                        <h6 class="fw-bold text-info text-uppercase mb-2"><i class="fas fa-file-invoice-dollar me-1"></i> Thông Tin Xuất Hóa Đơn VAT</h6>
                        <div class="d-flex flex-wrap gap-4">
                            <div><span class="text-muted small">Tên Công Ty:</span><br><strong><?php echo htmlspecialchars($order['vat_company_name']); ?></strong></div>
                            <div><span class="text-muted small">Mã Số Thuế:</span><br><strong><?php echo htmlspecialchars($order['vat_tax_code']); ?></strong></div>
                            <div><span class="text-muted small">Địa Chỉ:</span><br><strong><?php echo htmlspecialchars($order['vat_company_address']); ?></strong></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

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
                            <?php if(isset($order['member_discount']) && $order['member_discount'] > 0): ?>
                            <tr>
                                <td colspan="4" class="text-end text-success py-2"><i class="fas fa-star me-1"></i>Giảm hạng thành viên:</td>
                                <td class="text-end fw-bold text-success">-<?php echo number_format($order['member_discount']); ?>đ</td>
                            </tr>
                            <?php endif; ?>
                            <?php if(isset($order['coupon_discount']) && $order['coupon_discount'] > 0): ?>
                            <tr>
                                <td colspan="4" class="text-end text-success py-2">
                                    <i class="fas fa-tag me-1"></i>Mã giảm giá
                                    <?php if(!empty($order['coupon_code'])): ?>
                                        (<code><?php echo htmlspecialchars($order['coupon_code']); ?></code>):
                                    <?php else: ?>:
                                    <?php endif; ?>
                                </td>
                                <td class="text-end fw-bold text-success">-<?php echo number_format($order['coupon_discount']); ?>đ</td>
                            </tr>
                            <?php endif; ?>
                            <?php if(isset($order['points_used']) && $order['points_used'] > 0): ?>
                            <tr>
                                <td colspan="4" class="text-end text-success py-2"><i class="fas fa-coins me-1"></i>Sử dụng điểm (<?php echo number_format($order['points_used']); ?> điểm):</td>
                                <td class="text-end fw-bold text-success">-<?php echo number_format($order['points_used']); ?>đ</td>
                            </tr>
                            <?php endif; ?>
                            <?php if(isset($order['shipping_fee'])): ?>
                            <tr>
                                <td colspan="4" class="text-end text-muted py-2"><i class="fas fa-truck me-1"></i>Phí giao hàng:</td>
                                <td class="text-end fw-bold"><?php echo $order['shipping_fee'] > 0 ? number_format($order['shipping_fee']).'đ' : '<span class="text-success">Miễn phí</span>'; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(isset($order['vat_amount']) && $order['vat_amount'] > 0): ?>
                            <tr>
                                <td colspan="4" class="text-end text-muted py-2"><i class="fas fa-percent me-1"></i>Thuế VAT:</td>
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

                <!-- Ghi chú đơn hàng -->
                <?php if(!empty($order['note'])): ?>
                <div class="mb-4 p-3 bg-light rounded-3 border-start border-4 border-warning">
                    <h6 class="fw-bold text-muted text-uppercase mb-1"><i class="fas fa-comment-dots me-1"></i> Ghi chú</h6>
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($order['note'])); ?></p>
                </div>
                <?php endif; ?>

                <!-- KÊU GỌI ĐÁNH GIÁ SẢN PHẨM (CHỈ KHI ĐƠN HÀNG ĐÃ HOÀN THÀNH) -->
                <?php if($order['status'] == 'Hoàn thành' || $order['status'] == 'Hoan thanh'): ?>
                <div class="mb-4 p-4 rounded-4 text-center shadow-sm border no-print" style="background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);">
                    <h5 class="fw-bold mb-2"><i class="fas fa-star text-warning me-2"></i>Trải nghiệm của bạn thế nào?</h5>
                    <p class="text-muted mb-4 small">Đánh giá sản phẩm ngay để nhận thêm điểm thưởng và giúp mọi người hiểu rõ hơn về chất lượng nhé!</p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <?php foreach ($orderDetails as $item): ?>
                            <a href="index.php?controller=product&action=detail&id=<?php echo $item['product_id']; ?>#reviews" class="btn btn-outline-dark rounded-pill btn-sm px-3 fw-bold">
                                <i class="fas fa-edit me-1"></i> Đánh giá <?php echo mb_strimwidth(htmlspecialchars($item['name']), 0, 20, "..."); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

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