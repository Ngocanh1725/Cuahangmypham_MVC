<?php 
$pageTitle = "Quản lý Mã Giảm Giá - Glow Admin"; 
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                <div>
                    <h3 class="fw-bold text-dark"><i class="fas fa-ticket-alt me-2 text-warning"></i>Mã Giảm Giá (Coupons)</h3>
                    <p class="text-muted mb-0">Tạo và quản lý voucher khuyến mãi cho khách hàng</p>
                </div>
                <a href="index.php?controller=admin&action=addCoupon" class="btn btn-brand shadow-sm">
                    <i class="fas fa-plus me-2"></i> Thêm mã mới
                </a>
            </div>

            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>Thao tác thành công! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>
            
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3">Mã</th>
                                <th class="py-3">Loại</th>
                                <th class="py-3">Giá trị</th>
                                <th class="py-3">Đơn tối thiểu</th>
                                <th class="py-3">Đã dùng</th>
                                <th class="py-3">Hết hạn</th>
                                <th class="py-3">Trạng thái</th>
                                <th class="text-end pe-4 py-3">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($coupons)): ?>
                                <?php foreach($coupons as $c): ?>
                                    <tr>
                                        <td class="ps-4"><span class="badge bg-dark fw-bold fs-6 px-3 py-2"><?= htmlspecialchars($c['code']) ?></span></td>
                                        <td>
                                            <?php if($c['type'] == 'percent'): ?>
                                                <span class="badge bg-info">Phần trăm</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Số tiền</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?php if($c['type'] == 'percent'): ?>
                                                <?= number_format($c['discount_value']) ?>%
                                                <?php if($c['max_discount']): ?><br><small class="text-muted">Tối đa <?= number_format($c['max_discount']) ?>đ</small><?php endif; ?>
                                            <?php else: ?>
                                                <?= number_format($c['discount_value']) ?>đ
                                            <?php endif; ?>
                                        </td>
                                        <td><?= number_format($c['min_order_value']) ?>đ</td>
                                        <td>
                                            <span class="fw-bold"><?= $c['used_count'] ?></span>
                                            <?php if($c['usage_limit']): ?> / <?= $c['usage_limit'] ?><?php else: ?> / ∞<?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($c['end_date']): ?>
                                                <?= date('d/m/Y', strtotime($c['end_date'])) ?>
                                                <?php if(strtotime($c['end_date']) < time()): ?><br><span class="badge bg-danger">Hết hạn</span><?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">Không giới hạn</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($c['is_active']): ?>
                                                <span class="badge bg-success px-3 py-2">Hoạt động</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary px-3 py-2">Đã tắt</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="index.php?controller=admin&action=editCoupon&id=<?= $c['id'] ?>" class="btn btn-sm btn-light text-primary me-1" title="Sửa"><i class="fas fa-edit"></i></a>
                                            <a href="index.php?controller=admin&action=deleteCoupon&id=<?= $c['id'] ?>" onclick="return confirm('Xóa mã giảm giá này?');" class="btn btn-sm btn-light text-danger" title="Xóa"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center py-5 text-muted">Chưa có mã giảm giá nào</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
