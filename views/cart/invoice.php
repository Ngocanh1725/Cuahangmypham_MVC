<?php 
$pageTitle = "Hóa Đơn #" . $order['id'] . " - Glow Cosmetics"; 
$extraCSS = "<style>
    .invoice-card { background: #fff; border-radius: 15px; padding: 40px; }
    .invoice-header { border-bottom: 2px dashed #eee; padding-bottom: 20px; margin-bottom: 20px; }
    @media print {
        body { background: white !important; }
        nav, footer, .no-print { display: none !important; }
        .invoice-card { box-shadow: none !important; padding: 0; margin: 0; }
    }
</style>";
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
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
                        <h6 class="fw-bold text-muted text-uppercase mb-2">Giao hàng đến</h6>
                        <p class="mb-1"><strong>Khách hàng:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                        <p class="mb-1"><strong>SĐT:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                        <p class="mb-0"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></p>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                        <h6 class="fw-bold text-muted text-uppercase mb-2">Trạng thái thanh toán</h6>
                        <p class="mb-2 fw-bold text-primary">
                            <?php 
                                if(isset($order['payment_method']) && !empty($order['payment_method'])) {
                                    echo $order['payment_method'];
                                } else {
                                    echo "Thanh toán khi nhận hàng (COD)";
                                }
                            ?>
                        </p>
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><?php echo $order['status']; ?></span>
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
                            foreach ($orderDetails as $item) {
                                $thanh_tien = $item['price'] * $item['quantity'];
                                echo "
                                <tr>
                                    <td class='text-muted fw-bold'>$stt</td>
                                    <td class='fw-bold text-dark'>{$item['name']}</td>
                                    <td class='text-center'>".number_format($item['price'])."đ</td>
                                    <td class='text-center fw-bold'>{$item['quantity']}</td>
                                    <td class='text-end text-danger fw-bold'>".number_format($thanh_tien)."đ</td>
                                </tr>
                                ";
                                $stt++;
                            }
                            ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold py-3 fs-5">Tổng Thanh Toán:</td>
                                <td class="text-end fs-4 fw-bold" style="color: var(--brand-dark, #be185d);"><?php echo number_format($order['total_price']); ?>đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Nút điều hướng -->
                <div class="d-flex justify-content-center gap-3 mt-5 no-print border-top pt-4">
                    <a href="index.php" class="btn btn-light border px-4 rounded-pill fw-bold"><i class="fas fa-arrow-left me-2"></i>Về trang chủ</a>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?controller=user&action=orders" class="btn btn-outline-primary px-4 rounded-pill fw-bold"><i class="fas fa-list me-2"></i>Đơn mua của tôi</a>
                    <?php endif; ?>
                    
                    <button onclick="window.print()" class="btn btn-dark px-4 rounded-pill fw-bold"><i class="fas fa-print me-2"></i>In hóa đơn lưu trữ</button>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>