<?php 
$pageTitle = "Thống kê Doanh Thu - Glow Admin"; 
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                <div>
                    <a href="index.php?controller=admin&action=index" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Quay lại Dashboard</a>
                    <h3 class="fw-bold text-dark">Bảng Thống Kê Doanh Thu</h3>
                    <p class="text-muted">Danh sách các đơn hàng đã hoàn thành và đóng góp vào tổng doanh thu</p>
                </div>
            </div>

            <!-- Box Thống kê luồng tiền (MỚI THÊM) -->
            <div class="row mb-4 g-3">
                <div class="col-md-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm border h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-3"><i class="fas fa-wallet fs-5"></i></div>
                            <h6 class="text-muted mb-0 text-uppercase fw-bold" style="font-size: 0.8rem;">Tổng doanh thu</h6>
                        </div>
                        <h3 class="fw-bold mb-0 text-dark"><?php echo number_format($totalRevenue); ?>đ</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm border h-100 border-start border-success border-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success bg-opacity-10 text-success p-2 rounded-3 me-3"><i class="fas fa-money-bill-wave fs-5"></i></div>
                            <h6 class="text-muted mb-0 text-uppercase fw-bold" style="font-size: 0.8rem;">Tiền mặt (COD)</h6>
                        </div>
                        <h3 class="fw-bold mb-0 text-success"><?php echo isset($revenueBreakdown['cod']) ? number_format($revenueBreakdown['cod']) : 0; ?>đ</h3>
                        <p class="small text-muted mb-0 mt-1">Đã thu qua Cửa hàng / Shipper</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm border h-100 border-start border-info border-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-info bg-opacity-10 text-info p-2 rounded-3 me-3"><i class="fas fa-qrcode fs-5"></i></div>
                            <h6 class="text-muted mb-0 text-uppercase fw-bold" style="font-size: 0.8rem;">Chuyển khoản (QR)</h6>
                        </div>
                        <h3 class="fw-bold mb-0 text-info"><?php echo isset($revenueBreakdown['qr']) ? number_format($revenueBreakdown['qr']) : 0; ?>đ</h3>
                        <p class="small text-muted mb-0 mt-1">Tiền đã vào tài khoản ngân hàng</p>
                    </div>
                </div>
            </div>
            
            <!-- Bảng dữ liệu -->
            <div class="card border-0 shadow-sm rounded-4 table-custom">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">Mã Đơn</th>
                                <th class="py-3">Khách hàng</th>
                                <th class="py-3">Số điện thoại</th>
                                <th class="py-3">Ngày hoàn thành</th>
                                <th class="py-3">Phương thức TT</th>
                                <th class="text-end pe-4 py-3">Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($revenues)): ?>
                                <?php foreach($revenues as $row): ?>
                                    <tr>
                                        <td class="ps-4 text-muted fw-bold">#ORD-<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['customer_phone']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['payment_method']); ?></span></td>
                                        <td class="text-end pe-4 fw-bold text-success">+<?php echo number_format($row['total_price']); ?>đ</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-receipt fa-3x text-muted mb-3 opacity-50"></i>
                                        <h5 class="text-muted">Chưa có đơn hàng nào được hoàn thành.</h5>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>