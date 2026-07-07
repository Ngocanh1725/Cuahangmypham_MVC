<?php 
$pageTitle = "Chi tiết Đơn hàng #" . $order['id'] . " - Glow Admin"; 
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-2 no-print">
                <div>
                    <a href="index.php?controller=admin&action=orders" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Quay lại Danh sách</a>
                    <h3 class="fw-bold text-dark">Chi tiết Đơn hàng #ORD-<?php echo str_pad($order['id'], 4, '0', STR_PAD_LEFT); ?></h3>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-dark shadow-sm"><i class="fas fa-print me-2"></i> In Đơn Hàng</button>
                </div>
            </div>

            <div class="row">
                <!-- Thông tin chung & Khách hàng -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="fas fa-info-circle text-primary me-2"></i>Thông tin chung</h5>
                            
                            <div class="mb-3">
                                <span class="text-muted d-block small">Trạng thái hiện tại:</span>
                                <?php 
                                    $statusClass = 'bg-secondary';
                                    if ($order['status'] == 'Chờ xử lý') $statusClass = 'bg-warning text-dark';
                                    if ($order['status'] == 'Đang giao') $statusClass = 'bg-info text-dark';
                                    if ($order['status'] == 'Hoàn thành') $statusClass = 'bg-success';
                                    if ($order['status'] == 'Hủy') $statusClass = 'bg-danger';
                                ?>
                                <span class="badge <?php echo $statusClass; ?> px-3 py-2 fs-6 mt-1 rounded-pill"><?php echo $order['status']; ?></span>
                            </div>
                            
                            <div class="mb-3">
                                <span class="text-muted d-block small">Ngày đặt hàng:</span>
                                <strong class="text-dark"><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></strong>
                            </div>

                            <div class="mb-4">
                                <span class="text-muted d-block small">Phương thức thanh toán:</span>
                                <strong class="text-primary"><?php echo htmlspecialchars($order['payment_method']); ?></strong>
                            </div>

                            <h5 class="fw-bold mb-3 mt-4 border-bottom pb-2"><i class="fas fa-user text-success me-2"></i>Thông tin Khách hàng</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="fas fa-user-tag text-muted me-2 width-20"></i> <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong></li>
                                <li class="mb-2"><i class="fas fa-phone-alt text-muted me-2 width-20"></i> <?php echo htmlspecialchars($order['customer_phone']); ?></li>
                                <li><i class="fas fa-map-marker-alt text-muted me-2 width-20"></i> <?php echo htmlspecialchars($order['customer_address']); ?></li>
                            </ul>

                            <div class="mt-4 pt-3 border-top">
                                <!-- NÚT CẬP NHẬT TRẠNG THÁI -->
                                <div class="dropdown no-print">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle w-100 rounded-pill" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-sync-alt me-1"></i> Cập nhật trạng thái
                                    </button>
                                    <ul class="dropdown-menu w-100 text-center shadow border-0">
                                        <li><a class="dropdown-item py-2" href="index.php?controller=admin&action=updateOrderStatus&id=<?php echo $order['id']; ?>&status=Đang giao"><i class="fas fa-truck text-primary me-2"></i> Đang giao</a></li>
                                        <li><a class="dropdown-item py-2" href="index.php?controller=admin&action=updateOrderStatus&id=<?php echo $order['id']; ?>&status=Hoàn thành"><i class="fas fa-check-circle text-success me-2"></i> Đã giao & Thu tiền (Hoàn thành)</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item py-2 text-danger" href="index.php?controller=admin&action=updateOrderStatus&id=<?php echo $order['id']; ?>&status=Hủy"><i class="fas fa-times-circle me-2"></i> Hủy đơn</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách sản phẩm -->
                <div class="col-md-8 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-0">
                            <div class="p-4 border-bottom">
                                <h5 class="fw-bold mb-0"><i class="fas fa-box-open text-warning me-2"></i>Chi tiết sản phẩm đã mua</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-muted">
                                        <tr>
                                            <th class="ps-4 py-3">Sản phẩm</th>
                                            <th class="text-center py-3">Đơn giá</th>
                                            <th class="text-center py-3">Số lượng</th>
                                            <th class="text-end pe-4 py-3">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if(!empty($orderDetails)):
                                            foreach($orderDetails as $item): 
                                                $imgSrc = !empty($item['image']) ? $item['image'] : 'https://via.placeholder.com/50';
                                                $thanh_tien = $item['price'] * $item['quantity'];
                                        ?>
                                            <tr>
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="img" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                                        <div class="ms-3">
                                                            <h6 class="mb-0 fw-bold text-dark"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                            <small class="text-muted">ID SP: #<?php echo $item['product_id']; ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center fw-medium"><?php echo number_format($item['price']); ?>đ</td>
                                                <td class="text-center fw-bold"><?php echo $item['quantity']; ?></td>
                                                <td class="text-end pe-4 fw-bold text-danger"><?php echo number_format($thanh_tien); ?>đ</td>
                                            </tr>
                                        <?php 
                                            endforeach; 
                                        else:
                                        ?>
                                            <tr><td colspan="4" class="text-center py-4">Không tìm thấy chi tiết sản phẩm.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-4 bg-light rounded-bottom-4">
                                <div class="d-flex justify-content-end align-items-center">
                                    <h5 class="fw-bold mb-0 me-3 text-muted">Tổng thanh toán:</h5>
                                    <h3 class="fw-bold mb-0" style="color: var(--brand-dark, #be185d);"><?php echo number_format($order['total_price']); ?>đ</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<style>
    .width-20 { width: 20px; text-align: center; }
    @media print {
        body { background: white !important; }
        .no-print, .admin-sidebar, nav, footer { display: none !important; }
        .col-md-10 { width: 100% !important; padding: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
    }
</style>

<?php include 'views/layout/footer.php'; ?>