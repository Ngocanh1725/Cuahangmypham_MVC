<?php 
// Truyền tiêu đề trang cho header.php
$pageTitle = "Đăng nhập hệ thống - Glow Cosmetics"; 
include 'views/layout/header.php'; 
?>

<!-- Bọc form trong div min-vh-100 để căn giữa màn hình theo chiều dọc -->
<div class="d-flex align-items-center justify-content-center min-vh-100 w-100" style="margin-top: -30px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    
                    <!-- Phần viền màu trang trí phía trên thẻ card -->
                    <div style="height: 6px; background: linear-gradient(90deg, #be185d, #db2777);"></div>

                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <!-- Icon User nổi bật -->
                            <i class="fas fa-user-circle fa-4x mb-3" style="color: var(--brand-dark, #be185d);"></i>
                            <h3 class="fw-bold text-dark mb-1">Đăng Nhập</h3>
                            <p class="text-muted small">Chào mừng bạn quay trở lại hệ thống!</p>
                        </div>
                        
                        <!-- Hiển thị thông báo lỗi từ Controller truyền sang -->
                        <?php if(!empty($message)) echo $message; ?>

                        <!-- Form gửi dữ liệu về UserController, action login -->
                        <form method="POST" action="index.php?controller=user&action=login">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Email</label>
                                <div class="input-group shadow-sm rounded-pill overflow-hidden">
                                    <span class="input-group-text bg-white border-end-0 text-muted ps-4"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 py-2 shadow-none" required placeholder="Nhập email...">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Mật khẩu</label>
                                <div class="input-group shadow-sm rounded-pill overflow-hidden">
                                    <span class="input-group-text bg-white border-end-0 text-muted ps-4"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 py-2 shadow-none" required placeholder="******">
                                </div>
                            </div>

                            <button type="submit" class="btn text-white w-100 py-3 fw-bold rounded-pill shadow-sm mb-4" style="background-color: var(--brand-main, #db2777); transition: all 0.3s;" onmouseover="this.style.backgroundColor='var(--brand-dark, #be185d)'" onmouseout="this.style.backgroundColor='var(--brand-main, #db2777)'">
                                Đăng Nhập <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </form>
                        
                        <div class="text-center mt-2">
                            <a href="index.php" class="text-decoration-none text-muted" style="transition: color 0.2s;" onmouseover="this.style.color='#be185d'" onmouseout="this.style.color='#6c757d'">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại trang chủ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>