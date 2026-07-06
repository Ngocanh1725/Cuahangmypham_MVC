<!-- 1. Dải thông báo (Announcement Bar) -->
<div class="rhode-announcement-bar">
    <span>✨ Miễn phí vận chuyển cho mọi đơn hàng từ 500k ✨</span>
</div>

<!-- 2. Navbar Lơ Lửng (Floating Navbar) -->
<div class="rhode-header-wrapper">
    <header class="rhode-navbar">
        
        <!-- Logo -->
        <a href="index.php" class="rhode-logo">
            glow.
        </a>

        <!-- Menu Điều Hướng (Desktop) -->
        <nav class="rhode-nav-menu d-none d-lg-flex">
            <a href="index.php">Trang chủ</a>
            <a href="index.php?controller=product&action=index">Cửa hàng</a>
            <a href="index.php?controller=brand&action=index">Thương hiệu</a>
            <a href="index.php?controller=page&action=stores">Hệ thống</a>
            <a href="index.php?controller=page&action=blog">Tạp chí</a>
        </nav>

        <!-- Cụm Icon Bên Phải -->
        <div class="rhode-nav-icons">
            
            <!-- Nút Tìm Kiếm -->
            <a href="index.php?controller=product&action=index" class="rhode-icon-btn" title="Tìm kiếm">
                <i class="fas fa-search"></i>
            </a>
            
            <!-- Nút Tài Khoản -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="dropdown d-inline-block">
                    <a href="#" class="rhode-icon-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Tài khoản">
                        <i class="far fa-user"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-3 border-0 shadow-lg" style="border-radius: 16px; padding: 10px;">
                        <li><h6 class="dropdown-header text-muted fw-bold">Chào, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h6></li>
                        <?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 2): ?>
                            <li><a class="dropdown-item rounded-3 py-2" href="index.php?controller=admin&action=index"><i class="fas fa-tachometer-alt me-2 text-muted"></i> Trang Quản Trị</a></li>
                            <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item rounded-3 py-2" href="index.php?controller=user&action=profile"><i class="fas fa-user-circle me-2 text-muted"></i> Hồ sơ cá nhân</a></li>
                        <li><a class="dropdown-item rounded-3 py-2" href="index.php?controller=user&action=orders"><i class="fas fa-box me-2 text-muted"></i> Lịch sử đơn hàng</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item rounded-3 py-2 text-danger fw-bold" href="index.php?controller=user&action=logout"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="index.php?controller=user&action=login" class="rhode-icon-btn" title="Đăng nhập">
                    <i class="far fa-user"></i>
                </a>
            <?php endif; ?>

            <!-- Nút Giỏ Hàng -->
            <a href="index.php?controller=cart&action=index" class="rhode-icon-btn" title="Giỏ hàng">
                <i class="fas fa-shopping-bag"></i>
                <?php 
                    $cart_count = 0;
                    if(isset($_SESSION['cart'])) {
                        foreach($_SESSION['cart'] as $qty) { $cart_count += $qty; }
                    }
                    if($cart_count > 0): 
                ?>
                <span class="rhode-cart-badge"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </a>

        </div>
    </header>
</div>