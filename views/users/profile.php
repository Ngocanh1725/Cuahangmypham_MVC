<?php 
$pageTitle = "Hồ sơ cá nhân - Glow Cosmetics"; 
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <!-- Cột Menu Trái -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body text-center p-4" style="background: linear-gradient(135deg, #fff1f2 0%, #fce7f3 100%); border-bottom: 1px solid #fbcfe8;">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['full_name']); ?>&background=be185d&color=fff&size=100" class="rounded-circle mb-3 border border-3 border-white shadow-sm" alt="Avatar">
                    <h5 class="fw-bold mb-1" style="color: var(--brand-dark, #be185d);"><?php echo htmlspecialchars($user['full_name']); ?></h5>
                    <p class="text-muted small mb-0">Khách hàng thành viên</p>
                </div>
                <div class="list-group list-group-flush">
                    <a href="index.php?controller=user&action=profile" class="list-group-item list-group-item-action p-3 fw-bold active" style="background-color: var(--brand-dark, #be185d); border-color: var(--brand-dark, #be185d);">
                        <i class="fas fa-user-cog me-2 w-20"></i> Thông tin tài khoản
                    </a>
                    <a href="index.php?controller=user&action=orders" class="list-group-item list-group-item-action p-3 text-muted">
                        <i class="fas fa-box-open me-2 w-20"></i> Lịch sử mua hàng
                    </a>
                    <a href="index.php?controller=user&action=logout" class="list-group-item list-group-item-action p-3 text-danger fw-bold">
                        <i class="fas fa-sign-out-alt me-2 w-20"></i> Đăng xuất
                    </a>
                </div>
            </div>
        </div>

        <!-- Cột Nội Dung Phải -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white p-4 border-bottom-0">
                    <h4 class="fw-bold mb-0" style="color: var(--brand-dark, #be185d);">Thông Tin Cá Nhân</h4>
                    <p class="text-muted small mt-1 mb-0">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                </div>
                <div class="card-body p-4 pt-0">
                    <?php if(!empty($message)) echo $message; ?>

                    <form method="POST" action="index.php?controller=user&action=profile">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium mt-2">Email đăng nhập</label>
                            </div>
                            <div class="col-md-9">
                                <!-- Email không cho phép sửa để đảm bảo luồng hoạt động -->
                                <input type="email" class="form-control bg-light" value="<?php echo htmlspecialchars($user['email']); ?>" readonly disabled>
                                <div class="form-text">Email không thể thay đổi.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium mt-2">Họ và tên</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                        </div>

                        <hr class="my-4 text-muted">

                        <h6 class="fw-bold mb-3"><i class="fas fa-lock me-2 text-secondary"></i>Đổi mật khẩu (Tùy chọn)</h6>
                        
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium mt-2">Mật khẩu mới</label>
                            </div>
                            <div class="col-md-9">
                                <input type="password" name="new_password" class="form-control" placeholder="Để trống nếu không muốn đổi">
                                <div class="form-text">Nếu không muốn đổi mật khẩu, vui lòng không nhập vào ô này.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-9 offset-md-3">
                                <button type="submit" class="btn text-white px-5 rounded-pill shadow-sm" style="background-color: var(--brand-dark, #be185d);">
                                    Lưu Thay Đổi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>