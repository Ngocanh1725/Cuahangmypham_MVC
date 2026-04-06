<?php 
$pageTitle = "Chi tiết Đơn hàng - Glow Admin"; 
// Thêm CSS để ẩn các nút khi nhấn In Hóa Đơn
$extraCSS = "<style>
    @media print {
        .sidebar, .btn, .dropdown, a.text-muted, .no-print { display: none !important; }
        .col-md-10 { width: 100% !important; padding: 0 !important; background: white !important; }
        body { background: white !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
    }
</style>";
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <!-- Thanh Header bổ sung (Ẩn khi In) -->
            <div class="d-flex justify-content-end align-items-center mb-4 gap-3 bg-white p-3 rounded-4 shadow-sm border no-print">
                <span class="fw-bold text-dark me-auto ps-2">
                    <i class="fas fa-user-circle text-primary me-2 fs-5 align-middle"></i>
                    Xin chào, <?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Admin'; ?>
                </span>
                <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-store me-2"></i> Xem cửa hàng
                </a>
                <a href="index.php?controller=user&action=logout" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                </a>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                <div>
                    <a href="index.php?controller=admin&action=orders" class="text-decoration-none text-muted mb-2 d-inline-block no-print"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
                    <h3 class="fw-bold text-dark">Chi tiết Mã Đơn: #ORD-<?php echo $order['id']; ?></h3>
                </div>
                <button onclick="window.print()" class="btn btn-outline-dark shadow-sm rounded-pill px-4 no-print">
                    <i class="fas fa-print me-2"></i> In đơn hàng
                </button>
            </div>
            
            <div class="row">
                <!-- Thông tin khách hàng -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold border-bottom pb-3 mb-3"><i class="fas fa-user-circle me-2 text-primary"></i>Thông tin người nhận</h5>
                            <p class="mb-2"><strong>Họ và tên:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                            <p class="mb-2"><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['customer_phone'] ?? 'Không có'); ?></p>
                            <p class="mb-2"><strong>Địa chỉ giao hàng:</strong> <?php echo htmlspecialchars($order['customer_address'] ?? 'Không có'); ?></p>
                            <p class="mb-2"><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>
                            
                            <?php
                            $statusClass = 'bg-secondary';
                            if($order['status'] == 'Hoàn thành') $statusClass = 'bg-success';
                            if($order['status'] == 'Đang giao') $statusClass = 'bg-primary';
                            if($order['status'] == 'Chờ xử lý') $statusClass = 'bg-warning text-dark';
                            if($order['status'] == 'Hủy') $statusClass = 'bg-danger';
                            ?>

                            <div class="mt-4 p-3 bg-light rounded-3 text-center border">
                                <span class="d-block text-muted mb-2">Trạng thái hiện tại:</span>
                                <span class="badge <?php echo $statusClass; ?> fs-6 px-4 py-2 rounded-pill mb-3"><?php echo $order['status']; ?></span>
                                
                                <div class="dropdown no-print">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle w-100 rounded-pill" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-sync-alt me-1"></i> Cập nhật trạng thái
                                    </button>
                                    <ul class="dropdown-menu w-100 text-center shadow border-0">
                                        <li><a class="dropdown-item py-2" href="index.php?controller=admin&action=updateOrderStatus&id=<?php echo $order['id']; ?>&status=Đang giao"><i class="fas fa-truck text-primary me-2"></i> Đang giao</a></li>
                                        <li><a class="dropdown-item py-2" href="index.php?controller=admin&action=updateOrderStatus&id=<?php echo $order['id']; ?>&status=Hoàn thành"><i class="fas fa-check-circle text-success me-2"></i> Hoàn thành</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item py-2 text-danger" href="index.php?controller=admin&action=updateOrderStatus&id=<?php echo $order['id']; ?>&status=Hủy"><i class="fas fa-times-circle me-2"></i> Hủy đơn</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chi tiết sản phẩm -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold border-bottom pb-3 mb-3"><i class="fas fa-box-open me-2 text-warning"></i>Sản phẩm đã đặt</h5>
                            
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-end">Đơn giá</th>
                                            <th class="text-end">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($orderDetails)): ?>
                                            <?php foreach ($orderDetails as $item): 
                                                $thanhTien = $item['price'] * $item['quantity'];
                                                $imgSrc = !empty($item['image']) && strpos($item['image'], 'http') !== false ? $item['image'] : 'assets/' . $item['image'];
                                                if (empty($item['image'])) $imgSrc = 'https://via.placeholder.com/50';
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="<?php echo $imgSrc; ?>" class="rounded-3 me-3 border" style="width: 50px; height: 50px; object-fit: cover;">
                                                            <span class="fw-bold text-dark"><?php echo htmlspecialchars($item['name']); ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center fw-bold"><?php echo $item['quantity']; ?></td>
                                                    <td class="text-end"><?php echo number_format($item['price']); ?>đ</td>
                                                    <td class="text-end text-danger fw-bold"><?php echo number_format($thanhTien); ?>đ</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="4" class="text-center text-muted py-4">Không có thông tin sản phẩm.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot class="border-top-0">
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold pt-4 fs-5">Tổng Thanh Toán:</td>
                                            <td class="text-end fw-bold text-danger pt-4 fs-4"><?php echo number_format($order['total_price']); ?>đ</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>