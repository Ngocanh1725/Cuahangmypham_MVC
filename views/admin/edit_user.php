<?php 
// Truyền tiêu đề trang cho header.php
$pageTitle = "Sửa Tài Khoản (MVC) - Glow Admin"; 
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <!-- Nhúng Sidebar MVC -->
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

            <!-- Form Nội Dung Chính -->
            <div class="row justify-content-center">
                <div class="col-md-6 mt-2">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3 border-0">
                            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-user-edit me-2 text-primary"></i>Cập nhật Tài Khoản #<?php echo $user['id']; ?></h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if(!empty($message)) echo $message; ?>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Họ và Tên</label>
                                    <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mật khẩu mới (Tùy chọn)</label>
                                    <input type="password" name="password" class="form-control" placeholder="Để trống nếu không muốn đổi mật khẩu">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-danger">Phân quyền</label>
                                    <select name="role" class="form-select border-danger">
                                        <option value="0" <?php if($user['role'] == 0) echo 'selected'; ?>>Khách hàng (User)</option>
                                        <option value="2" <?php if($user['role'] == 2) echo 'selected'; ?>>Nhân viên (Staff)</option>
                                        <option value="1" <?php if($user['role'] == 1) echo 'selected'; ?>>Quản trị viên (Admin)</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?controller=admin&action=users" class="btn btn-light px-4">Hủy</a>
                                    <button type="submit" class="btn btn-brand px-4">Lưu cập nhật</button>
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