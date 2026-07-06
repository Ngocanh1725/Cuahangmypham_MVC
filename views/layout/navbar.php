<!-- 1. Topbar (Thông tin phụ trên cùng) -->
<div class="topbar d-none d-lg-block">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Thông tin liên hệ -->
        <div class="topbar-left">
            <span class="me-4"><i class="fas fa-envelope me-1"></i> cskh@glow.com</span>
            <span><i class="fas fa-phone-alt me-1"></i> Hotline: 1900 8888</span>
        </div>
        <!-- Ngôn ngữ & Liên kết phụ -->
        <div class="topbar-right">
            <a href="index.php?controller=page&action=stores" class="me-4"><i class="fas fa-map-marker-alt me-1"></i> Tìm cửa hàng</a>
            <span>VN | VND</span>
        </div>
    </div>
</div>

<!-- 2. Main Header (Logo, Menu, Icons) -->
<header class="main-header sticky-top">
    <div class="container d-flex align-items-center justify-content-between">
        
        <!-- Logo -->
        <a href="index.php" class="brand-logo">
            glow.
        </a>

        <!-- Menu Điều Hướng (Căn giữa) -->
        <nav class="nav-menu d-none d-lg-flex align-items-center">
            <a href="index.php">Trang chủ</a>
            <a href="index.php?controller=product&action=index">Cửa hàng</a>
            <a href="index.php?controller=brand&action=index">Thương hiệu</a>
            <a href="index.php?controller=page&action=stores">Hệ thống</a>
            <a href="index.php?controller=page&action=blog">Tạp chí</a>
        </nav>

        <!-- Cụm Icon Bên Phải -->
        <div class="header-icons d-flex align-items-center">
            
            <!-- Nút Tìm Kiếm -->
            <a href="index.php?controller=product&action=index" title="Tìm kiếm">
                <i class="fas fa-search"></i>
            </a>
            
            <!-- Nút Tài Khoản (Đăng nhập/Đăng xuất/Profile) -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="dropdown d-inline-block">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false" title="Tài khoản">
                        <i class="far fa-user"></i>
                    </a>
                    <ul class="dropdown-menu custom-dropdown-menu dropdown-menu-end mt-3">
                        <li><h6 class="dropdown-header text-muted fw-bold">Chào, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h6></li>
                        <?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 2): ?>
                            <li><a class="dropdown-item" href="index.php?controller=admin&action=index"><i class="fas fa-tachometer-alt me-2 text-muted"></i> Trang Quản Trị</a></li>
                            <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="index.php?controller=user&action=profile"><i class="fas fa-user-circle me-2 text-muted"></i> Hồ sơ cá nhân</a></li>
                        <li><a class="dropdown-item" href="index.php?controller=user&action=orders"><i class="fas fa-box me-2 text-muted"></i> Lịch sử đơn hàng</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger fw-bold" href="index.php?controller=user&action=logout"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="index.php?controller=user&action=login" title="Đăng nhập"><i class="far fa-user"></i></a>
            <?php endif; ?>

            <!-- Nút Giỏ Hàng -->
            <a href="index.php?controller=cart&action=index" title="Giỏ hàng" class="position-relative">
                <i class="fas fa-shopping-bag"></i>
                <?php 
                    $cart_count = 0;
                    if(isset($_SESSION['cart'])) {
                        foreach($_SESSION['cart'] as $qty) { $cart_count += $qty; }
                    }
                    if($cart_count > 0): 
                ?>
                <span class="cart-badge"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </a>

        </div>
    </div>
</header>