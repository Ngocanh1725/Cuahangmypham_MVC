<!-- KHỐI CSS TẠO MÀU SẮC VÀ HIỆU ỨNG TRỰC TIẾP -->
<style>
    /* Thanh thông báo trên cùng chuẩn màu Tạp chí */
    .top-promo-bar { 
        background-color: #7A1C1C !important; 
        color: #ffffff !important; 
        font-size: 14px; 
        font-weight: 500; 
        padding: 10px 0;
    }
    
    /* Logo */
    .navbar-brand-text { 
        font-family: 'Playfair Display', serif; 
        font-size: 2.5rem; 
        font-weight: 700; 
        color: #7A1C1C !important; 
        letter-spacing: -1px; 
    }
    
    /* Các link menu bên trái */
    .nav-top-links a { 
        color: #7A1C1C !important; 
        text-decoration: none; 
        font-weight: 500; 
        font-size: 1.05rem; 
        margin-right: 35px; 
        transition: opacity 0.3s; 
    }
    .nav-top-links a:hover { opacity: 0.6; }
    
    /* Các icon bên phải */
    .nav-icons-right a { 
        color: #7A1C1C !important; 
        font-size: 1.3rem; 
        margin-left: 25px; 
        text-decoration: none;
        transition: transform 0.2s;
    }
    .nav-icons-right a:hover { transform: scale(1.1); }
    
    /* Ẩn mũi tên mặc định của Bootstrap Dropdown */
    .dropdown-toggle-no-caret::after { display: none !important; }

    /* Thanh Menu phụ (Pill Nav) */
    .pill-nav-container { border-bottom: 1px solid #f0f0f0; background: #fff; }
    .pill-nav .nav-link {
        padding: 12px 20px;
        color: #333 !important;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s ease;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .pill-nav .nav-link:hover { color: #7A1C1C !important; background-color: #fdfaf7; }
    .pill-nav .nav-link.active-pill { 
        color: #7A1C1C !important; 
        font-weight: 700 !important; 
        border-bottom: 2px solid #7A1C1C; 
    }

    /* Dropdown tìm kiếm */
    .search-dropdown-menu {
        min-width: 300px;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
</style>

<!-- Tầng 1: Top Promo Bar (Màu Đỏ Tía) -->
<div class="top-promo-bar text-center">
    Miễn phí vận chuyển cho mọi đơn hàng từ 500.000đ
</div>

<!-- Tầng 2: Main Header (Left Links - Center Logo - Right Icons) -->
<header class="py-3 bg-white sticky-top shadow-sm">
    <div class="container d-flex align-items-center justify-content-between">
        
        <!-- Bên Trái: Menu Links -->
        <div class="nav-top-links d-none d-lg-flex align-items-center flex-1" style="flex: 1;">
            <a href="index.php?controller=product&action=index">Sản phẩm</a>
            <a href="index.php?controller=brand&action=index">Thương hiệu</a>
            <a href="index.php?controller=page&action=stores">Về chúng tôi</a>
        </div>
        
        <!-- Ở Giữa: Logo -->
        <div class="text-center d-flex justify-content-center" style="flex: 1;">
            <a href="index.php" class="navbar-brand-text text-decoration-none">
                glow.
            </a>
        </div>

        <!-- Bên Phải: Icons -->
        <div class="d-flex align-items-center justify-content-end nav-icons-right" style="flex: 1;">
            
            <!-- Icon Tài khoản -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="dropdown d-inline-block">
                    <a href="#" class="dropdown-toggle-no-caret" data-bs-toggle="dropdown" aria-expanded="false" title="Tài khoản của tôi">
                        <i class="far fa-user"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" style="border-radius: 8px; min-width: 220px;">
                        <li><h6 class="dropdown-header text-truncate fw-bold" style="color: #7A1C1C;">Xin chào, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h6></li>
                        <?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 2): ?>
                            <li><a class="dropdown-item py-2" href="index.php?controller=admin&action=index"><i class="fas fa-tachometer-alt text-muted me-2"></i> Trang Quản Trị</a></li>
                            <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item py-2" href="index.php?controller=user&action=orders"><i class="fas fa-box text-muted me-2"></i> Đơn mua của tôi</a></li>
                        <li><a class="dropdown-item py-2" href="index.php?controller=user&action=profile"><i class="fas fa-user-cog text-muted me-2"></i> Hồ sơ cá nhân</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger fw-bold" href="index.php?controller=user&action=logout"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="index.php?controller=user&action=login" title="Đăng nhập"><i class="far fa-user"></i></a>
            <?php endif; ?>

            <!-- Icon Tìm kiếm (Mở popup Dropdown) -->
            <div class="dropdown d-inline-block">
                <a href="#" class="dropdown-toggle-no-caret" data-bs-toggle="dropdown" aria-expanded="false" title="Tìm kiếm">
                    <i class="fas fa-search"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end search-dropdown-menu mt-3 p-2">
                    <form action="index.php" method="GET" class="d-flex align-items-center bg-light rounded-pill px-3 py-1 m-0">
                        <input type="hidden" name="controller" value="product">
                        <input type="hidden" name="action" value="index">
                        <input type="text" name="keyword" class="form-control border-0 bg-transparent shadow-none" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" required>
                        <button type="submit" class="btn border-0 text-muted p-1"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <!-- Icon Giỏ hàng -->
            <a href="index.php?controller=cart&action=index" class="position-relative" title="Giỏ hàng">
                <i class="fas fa-shopping-bag"></i>
                <?php 
                    $cart_count = 0;
                    if(isset($_SESSION['cart'])) {
                        foreach($_SESSION['cart'] as $qty) { $cart_count += $qty; }
                    }
                    if($cart_count > 0): 
                ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px; padding: 3px 6px; background-color: #7A1C1C !important;">
                    <?php echo $cart_count; ?>
                </span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</header>

<!-- Tầng 3: Colorful Navigation (Pill Nav) -->
<?php 
    $currentFilter = isset($_GET['filter']) ? $_GET['filter'] : ''; 
    $currentController = isset($_GET['controller']) ? $_GET['controller'] : 'product';
?>
<nav class="pill-nav-container mb-4 d-none d-md-block">
    <div class="container">
        <div class="d-flex justify-content-center flex-wrap pill-nav">
            <a href="index.php?controller=brand&action=index" class="nav-link <?php echo ($currentController == 'brand') ? 'active-pill' : ''; ?>">Thương hiệu</a>
            <a href="index.php?controller=product&action=index&filter=promotion" class="nav-link <?php echo ($currentFilter == 'promotion') ? 'active-pill' : ''; ?>">Khuyến mãi hot</a>
            <a href="index.php?controller=product&action=index&filter=makeup" class="nav-link <?php echo ($currentFilter == 'makeup') ? 'active-pill' : ''; ?>">Trang điểm</a>
            <a href="index.php?controller=product&action=index&filter=skincare" class="nav-link <?php echo ($currentFilter == 'skincare') ? 'active-pill' : ''; ?>">Chăm Sóc Da</a>
            <a href="index.php?controller=product&action=index&filter=hairbody" class="nav-link <?php echo ($currentFilter == 'hairbody') ? 'active-pill' : ''; ?>">Cơ thể & Tóc</a>
            <a href="index.php?controller=product&action=index&filter=new" class="nav-link <?php echo ($currentFilter == 'new') ? 'active-pill' : ''; ?>">Mới ra mắt</a>
            <a href="index.php?controller=product&action=index&filter=all" class="nav-link <?php echo ($currentFilter == 'all' || ($currentFilter == '' && $currentController == 'product')) ? 'active-pill' : ''; ?>">Tất cả</a>
        </div>
    </div>
</nav>