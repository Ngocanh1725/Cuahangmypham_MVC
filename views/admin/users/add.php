<?php 
$pageTitle = "Thêm Tài Khoản (MVC) - Glow Admin"; 
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

            <div class="row justify-content-center">
                <div class="col-md-6 mt-4">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3 border-0">
                            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-user-plus me-2 text-danger"></i>Thêm Tài Khoản Mới</h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if(!empty($message)) echo $message; ?>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Họ và Tên</label>
                                    <input type="text" name="fullname" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email đăng nhập</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mật khẩu</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-danger">Phân quyền</label>
                                    <select name="role" class="form-select border-danger" id="role-select">
                                        <option value="0">Khách hàng (User)</option>
                                        <option value="2">Nhân viên (Staff)</option>
                                        <option value="1">Quản trị viên (Admin)</option>
                                    </select>
                                </div>

                                <div class="mb-4 bg-light p-3 border rounded-3" id="permissions-block" style="display: none;">
                                    <label class="form-label fw-bold text-dark mb-3"><i class="fas fa-shield-alt me-2 text-warning"></i>Phân quyền chức năng</label>
                                    <div class="row">
                                        <?php 
                                        $modules = [
                                            'products' => 'Quản lý Sản phẩm',
                                            'banners' => 'Quản lý Banner',
                                            'brands' => 'Quản lý Thương hiệu',
                                            'coupons' => 'Quản lý Mã giảm giá',
                                            'orders' => 'Quản lý Đơn hàng',
                                            'posts' => 'Quản lý Bài viết',
                                            'tiers' => 'Quản lý Hạng thành viên',
                                            'stores' => 'Quản lý Chi nhánh',
                                            'users' => 'Quản lý Người dùng',
                                            'settings' => 'Cấu hình & Cài đặt'
                                        ];
                                        foreach($modules as $key => $name):
                                        ?>
                                        <div class="col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="<?php echo $key; ?>" id="perm_<?php echo $key; ?>">
                                                <label class="form-check-label text-dark" for="perm_<?php echo $key; ?>">
                                                    <?php echo $name; ?>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?controller=admin&action=users" class="btn btn-light px-4">Hủy</a>
                                    <button type="submit" class="btn btn-brand px-4">Tạo tài khoản</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const roleSelect = document.getElementById('role-select');
    const permBlock = document.getElementById('permissions-block');
    
    function togglePermissions() {
        if (roleSelect.value === '2') {
            permBlock.style.display = 'block';
        } else {
            permBlock.style.display = 'none';
            // Bỏ check tất cả nếu không phải nhân viên
            const checkboxes = permBlock.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);
        }
    }
    
    roleSelect.addEventListener('change', togglePermissions);
    togglePermissions(); // Khởi tạo lần đầu
});
</script>