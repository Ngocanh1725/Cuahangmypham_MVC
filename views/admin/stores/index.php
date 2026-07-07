<?php 
$pageTitle = "Chi Nhánh Cửa Hàng - Glow Admin"; 
include 'views/layout/header.php'; 
?>
<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                <div>
                    <h3 class="fw-bold text-dark"><i class="fas fa-store me-2 text-success"></i>Chi Nhánh Cửa Hàng</h3>
                    <p class="text-muted mb-0">Quản lý địa điểm để khách chọn "Lấy tại cửa hàng"</p>
                </div>
                <a href="index.php?controller=admin&action=addStore" class="btn btn-brand shadow-sm">
                    <i class="fas fa-plus me-2"></i> Thêm chi nhánh
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
                                <th class="ps-4 py-3">ID</th>
                                <th class="py-3">Tên chi nhánh</th>
                                <th class="py-3">Địa chỉ</th>
                                <th class="py-3">Thành phố</th>
                                <th class="py-3">SĐT</th>
                                <th class="py-3">Giờ mở cửa</th>
                                <th class="py-3">Trạng thái</th>
                                <th class="text-end pe-4 py-3">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($stores)): ?>
                                <?php foreach($stores as $s): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-muted">#<?= $s['id'] ?></td>
                                        <td class="fw-bold"><?= htmlspecialchars($s['name']) ?></td>
                                        <td class="small"><?= htmlspecialchars($s['address']) ?></td>
                                        <td><span class="badge bg-light text-dark"><?= htmlspecialchars($s['city']) ?></span></td>
                                        <td><?= htmlspecialchars($s['phone']) ?></td>
                                        <td><i class="far fa-clock me-1"></i><?= htmlspecialchars($s['open_hours']) ?></td>
                                        <td>
                                            <?php if($s['is_active']): ?>
                                                <span class="badge bg-success px-3 py-2">Mở cửa</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary px-3 py-2">Đóng cửa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="index.php?controller=admin&action=editStore&id=<?= $s['id'] ?>" class="btn btn-sm btn-light text-primary me-1"><i class="fas fa-edit"></i></a>
                                            <a href="index.php?controller=admin&action=deleteStore&id=<?= $s['id'] ?>" onclick="return confirm('Xóa chi nhánh này?');" class="btn btn-sm btn-light text-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center py-5 text-muted">Chưa có chi nhánh nào</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'views/layout/footer.php'; ?>
