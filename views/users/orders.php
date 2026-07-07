<?php 
$pageTitle = "Lịch sử mua hàng - Glow Cosmetics"; 
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold mb-0" style="color: var(--brand-dark, #be185d);">Đơn Mua Của Tôi</h2>
            <p class="text-muted mt-2">Theo dõi trạng thái các đơn hàng bạn đã đặt</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="index.php" class="btn btn-light border rounded-pill shadow-sm px-4">
                <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 table-custom">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4 py-3">Mã đơn</th>
                            <th class="py-3">Ngày đặt</th>
                            <th class="py-3">Sản phẩm</th>
                            <th class="py-3">Tổng tiền</th>
                            <th class="py-3">Trạng thái</th>
                            <th class="text-end pe-4 py-3">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $row): 
                                $statusClass = 'bg-secondary';
                                if($row['status'] == 'Hoàn thành') $statusClass = 'bg-success';
                                if($row['status'] == 'Đang giao') $statusClass = 'bg-primary';
                                if($row['status'] == 'Chờ xử lý') $statusClass = 'bg-warning text-dark';
                                if($row['status'] == 'Đã hủy') $statusClass = 'bg-danger';
                            ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#ORD-<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?></td>
                                    <td>
                                        <span class="text-truncate d-inline-block text-muted" style="max-width: 200px;">
                                            Gói hàng mỹ phẩm Glow
                                        </span>
                                    </td>
                                    <td class="fw-bold text-danger"><?php echo number_format($row['total_price']); ?>đ</td>
                                    <td><span class="badge <?php echo $statusClass; ?> bg-opacity-75 px-3 py-2 rounded-pill"><?php echo $row['status']; ?></span></td>
                                    <td class="text-end pe-4">
                                        <!-- Tận dụng luôn trang Hóa Đơn làm trang xem chi tiết -->
                                        <a href="index.php?controller=cart&action=invoice&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fas fa-eye me-1"></i> Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                                    <h5 class="text-muted">Bạn chưa có đơn hàng nào.</h5>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>