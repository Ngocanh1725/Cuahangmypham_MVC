<?php 
$pageTitle = "Quản lý Tồn kho - Glow Admin"; 
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Nhật ký Tồn kho & Nhập hàng</h4>
                <a href="index.php?controller=admin&action=addStock" class="btn btn-success"><i class="fas fa-plus"></i> Nhập hàng mới</a>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 py-2">Lịch sử xuất/nhập kho gần đây</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Sản phẩm</th>
                                    <th>Nhà cung cấp</th>
                                    <th>Thay đổi</th>
                                    <th>Lý do</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($logs)): foreach($logs as $log): ?>
                                <tr>
                                    <td class="text-muted"><small><?php echo date('d/m/Y H:i', strtotime($log['created_at'])); ?></small></td>
                                    <td><strong class="text-dark"><?php echo htmlspecialchars($log['product_name']); ?></strong></td>
                                    <td><?php echo $log['supplier_name'] ? htmlspecialchars($log['supplier_name']) : '<span class="text-muted">N/A</span>'; ?></td>
                                    <td>
                                        <?php if ($log['change_amount'] > 0): ?>
                                            <span class="badge bg-success">+<?php echo $log['change_amount']; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><?php echo $log['change_amount']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($log['reason']); ?></td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr><td colspan="5" class="text-center py-4">Chưa có lịch sử tồn kho.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
