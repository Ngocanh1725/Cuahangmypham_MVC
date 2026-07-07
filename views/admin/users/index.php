<?php 
$pageTitle = "Quản lý Tài khoản (MVC) - Glow Admin"; 
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <!-- Thanh Header bổ sung Đăng xuất / Về trang chủ trực tiếp -->
            <div class="d-flex justify-content-end align-items-center mb-4 gap-3 bg-white p-3 rounded-4 shadow-sm border">
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
                    <h3 class="fw-bold text-dark">Tài khoản & Phân quyền</h3>
                    <p class="text-muted">Quản lý quản trị viên, nhân viên và khách hàng</p>
                </div>
                <?php if ($_SESSION['role'] == 1): ?>
                    <a href="index.php?controller=admin&action=addUser" class="btn btn-brand shadow-sm">
                        <i class="fas fa-user-plus me-2"></i> Tạo tài khoản mới
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 table-custom">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">ID</th>
                                <th class="py-3">Họ và Tên</th>
                                <th class="py-3">Email</th>
                                <th class="py-3">Vai trò</th>
                                <th class="text-end pe-4 py-3">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $row): 
                                    if ($row['role'] == 1) {
                                        $roleBadge = '<span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill"><i class="fas fa-crown me-1"></i> Admin</span>';
                                    } elseif ($row['role'] == 2) {
                                        $roleBadge = '<span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill"><i class="fas fa-user-tie me-1"></i> Nhân viên</span>';
                                    } else {
                                        $roleBadge = '<span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">Khách hàng</span>';
                                    }
                                ?>
                                    <tr>
                                        <td class='ps-4 fw-bold text-muted'>#<?php echo $row['id']; ?></td>
                                        <td class='fw-bold text-dark'><?php echo htmlspecialchars($row['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo $roleBadge; ?></td>
                                        <td class='text-end pe-4'>
                                            <?php if ($_SESSION['role'] == 1): ?>
                                                <a href='index.php?controller=admin&action=editUser&id=<?php echo $row['id']; ?>' class='btn btn-sm btn-light text-primary me-2 rounded-circle' title='Sửa'>
                                                    <i class='fas fa-edit'></i>
                                                </a>
                                                <?php if ($row['id'] != $_SESSION['user_id']): ?>
                                                    <a href='index.php?controller=admin&action=deleteUser&id=<?php echo $row['id']; ?>' onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');" class='btn btn-sm btn-light text-danger rounded-circle' title='Xóa'>
                                                        <i class='fas fa-trash'></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted small"><i class="fas fa-lock me-1"></i> Chỉ Admin</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='5' class='text-center py-5 text-muted'>Chưa có tài khoản nào</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>