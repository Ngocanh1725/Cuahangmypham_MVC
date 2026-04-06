<!-- KHỐI CSS TẠO MÀU SẮC VÀ HIỆU ỨNG -->
<style>
    /* Topbar & Search */
    .top-promo-bar { background-color: #6a2c91; font-size: 13px; }
    .search-box-custom { background-color: #f5f5f5; border-radius: 50px; padding: 8px 20px; }
    .search-box-custom input { border: none; background: transparent; outline: none; width: 100%; box-shadow: none; }

    /* Pill Navigation (Thanh menu nhiều màu) */
    .pill-nav .nav-link {
        padding: 8px 24px;
        border-radius: 50px;
        color: #222 !important;
        font-weight: 500;
        font-size: 15px;
        transition: all 0.2s ease;
        white-space: nowrap;
        margin: 0 5px;
    }
    .pill-nav .nav-link:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    
    /* Trạng thái đang chọn (Active) */
    .pill-nav .nav-link.active-pill {
        border: 2px solid #444;
        font-weight: 700 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.12);
    }
    
    /* Bảng màu chuẩn Pastel */
    .bg-pill-1 { background-color: #fce4ec; } 
    .bg-pill-2 { background-color: #ffe0b2; } 
    .bg-pill-3 { background-color: #ffcdd2; } 
    .bg-pill-4 { background-color: #dcedc8; } 
    .bg-pill-5 { background-color: #b3e5fc; } 
    .bg-pill-6 { background-color: #b2dfdb; } 
</style>

<!-- Tầng 1: Top Promo Bar -->
<div class="top-promo-bar text-white text-center py-2">
    Freeship 15K mọi đơn hàng &nbsp;&nbsp;&middot;&nbsp;&nbsp; Mua là có quà &nbsp;&nbsp;&middot;&nbsp;&nbsp; Mua online nhận tại cửa hàng gần nhất
</div>

<!-- Tầng 2: Main Header -->
<header class="py-3 border-bottom bg-white">
    <div class="container d-flex align-items-center justify-content-between">
        
        <!-- Logo -->
        <a href="index.php" class="text-dark text-decoration-none fs-3 fw-bold tracking-tight d-flex align-items-center">
            <i class="fas fa-spa me-2" style="color: #be185d;"></i>GLOW <span style="color: #be185d;" class="ms-1">STORE</span>
        </a>

        <!-- Thanh Tìm Kiếm -->
        <form action="index.php" method="GET" class="search-box-custom d-flex align-items-center flex-grow-1 mx-5 mb-0">
            <input type="hidden" name="controller" value="product">
            <input type="hidden" name="action" value="index">
            <button type="submit" class="border-0 bg-transparent p-0" title="Tìm kiếm">
                <i class="fas fa-search text-muted me-2"></i>
            </button>
            <input type="text" name="keyword" placeholder="Tìm kiếm sản phẩm, thương hiệu..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
        </form>

        <!-- Các nút công cụ -->
        <div class="d-flex align-items-center gap-4">
            <a href="index.php?controller=page&action=stores" class="text-dark text-decoration-none fw-medium d-none d-lg-block">
                <i class="fas fa-store"></i> Hệ thống cửa hàng
            </a>
            
            <a href="index.php?controller=page&action=blog" class="text-dark text-decoration-none fw-medium d-none d-lg-block">
                <i class="fas fa-book-open"></i> Tạp chí làm đẹp
            </a>

            <!-- DẤU 3 CHẤM ĐÃ ĐƯỢC LÀM LẠI ICON GIỐNG BEAUTY BOX -->
            <div class="dropdown">
                <a href="#" class="text-dark fs-5 text-decoration-none dropdown-toggle-no-caret" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0 5px;">
                    <i class="fas fa-ellipsis-h"></i>
                </a>
                <ul class="dropdown-menu shadow-sm border-0 dropdown-menu-end" style="border-radius: 8px; min-width: 220px; padding: 10px 0; margin-top: 15px;">
                    <li><a class="dropdown-item py-2 px-3 d-flex align-items-center" href="index.php?controller=page&action=support"><i class="far fa-comments text-dark me-3 opacity-75" style="font-size: 1.1rem; width: 20px; text-align: center;"></i> <span style="font-size: 15px; font-weight: 500;">Trung tâm hỗ trợ</span></a></li>
                    <li><a class="dropdown-item py-2 px-3 d-flex align-items-center" href="index.php?controller=user&action=orders"><i class="fas fa-box-open text-dark me-3 opacity-75" style="font-size: 1.1rem; width: 20px; text-align: center;"></i> <span style="font-size: 15px; font-weight: 500;">Tra cứu đơn hàng</span></a></li>
                    <li><a class="dropdown-item py-2 px-3 d-flex align-items-center" href="index.php?controller=page&action=events"><i class="fas fa-store-alt text-dark me-3 opacity-75" style="font-size: 1.1rem; width: 20px; text-align: center;"></i> <span style="font-size: 15px; font-weight: 500;">Sự kiện tại store</span></a></li>
                </ul>
            </div>
            <!-- Bỏ class mũi tên mặc định của Bootstrap Dropdown -->
            <style>.dropdown-toggle-no-caret::after { display: none !important; }</style>

            <div class="vr"></div> <!-- Dấu gạch dọc phân cách -->

            <!-- === XỬ LÝ NÚT ĐĂNG NHẬP / ĐĂNG XUẤT === -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="dropdown">
                    <a href="#" class="text-dark text-decoration-none fw-medium d-flex align-items-center gap-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle fs-4" style="color: #6a2c91;"></i> 
                        <span class="d-none d-md-inline fw-bold"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" style="border-radius: 12px; min-width: 220px;">
                        <?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 2): ?>
                            <li><a class="dropdown-item py-2" href="index.php?controller=admin&action=index"><i class="fas fa-tachometer-alt text-primary me-2"></i> Trang Quản Trị</a></li>
                            <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item py-2" href="index.php?controller=user&action=orders"><i class="fas fa-box text-secondary me-2"></i> Đơn mua của tôi</a></li>
                        <li><a class="dropdown-item py-2" href="index.php?controller=user&action=profile"><i class="fas fa-user-cog text-secondary me-2"></i> Hồ sơ cá nhân</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger fw-bold" href="index.php?controller=user&action=logout"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="index.php?controller=user&action=login" class="text-dark text-decoration-none fw-medium d-flex align-items-center gap-2">
                    <i class="far fa-user fs-4"></i> <span class="d-none d-md-inline">Đăng nhập</span>
                </a>
            <?php endif; ?>

            <!-- Giỏ hàng -->
            <a href="index.php?controller=cart&action=index" class="text-dark position-relative text-decoration-none">
                <i class="fas fa-shopping-bag fs-4"></i>
                <?php 
                    $cart_count = 0;
                    if(isset($_SESSION['cart'])) {
                        foreach($_SESSION['cart'] as $qty) { $cart_count += $qty; }
                    }
                    if($cart_count > 0): 
                ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                    <?php echo $cart_count; ?>
                </span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</header>

<!-- Tầng 3: Colorful Navigation (Pill Nav) -->
<?php 
    $currentFilter = isset($_GET['filter']) ? $_GET['filter'] : 'all'; 
    if(isset($_GET['controller']) && $_GET['controller'] != 'product' && $_GET['controller'] != '') {
        $currentFilter = ''; 
    }
?>
<nav class="bg-white py-3 shadow-sm mb-4">
    <div class="container">
        <div class="d-flex justify-content-center gap-3 flex-wrap pill-nav">
            <a href="index.php?controller=product&action=index&filter=all" class="nav-link bg-pill-1 <?php echo ($currentFilter == 'all' || $currentFilter == '') ? 'active-pill' : ''; ?>">Tất cả</a>
            <a href="index.php?controller=product&action=index&filter=promotion" class="nav-link bg-pill-2 <?php echo ($currentFilter == 'promotion') ? 'active-pill' : ''; ?>">Khuyến mãi hot</a>
            <a href="index.php?controller=product&action=index&filter=makeup" class="nav-link bg-pill-3 <?php echo ($currentFilter == 'makeup') ? 'active-pill' : ''; ?>">Trang điểm</a>
            <a href="index.php?controller=product&action=index&filter=skincare" class="nav-link bg-pill-4 <?php echo ($currentFilter == 'skincare') ? 'active-pill' : ''; ?>">Chăm Sóc Da Mặt</a>
            <a href="index.php?controller=product&action=index&filter=hairbody" class="nav-link bg-pill-5 <?php echo ($currentFilter == 'hairbody') ? 'active-pill' : ''; ?>">Chăm sóc cơ thể</a>
            <a href="index.php?controller=product&action=index&filter=new" class="nav-link bg-pill-6 <?php echo ($currentFilter == 'new') ? 'active-pill' : ''; ?>">Sản Phẩm Mới</a>
            <a href="index.php?controller=product&action=index&filter=perfume" class="nav-link bg-pill-7 <?php echo ($currentFilter == 'perfume') ? 'active-pill' : ''; ?>">Nước hoa</a>
        </div>
    </div>
</nav>