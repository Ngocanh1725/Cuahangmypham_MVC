<?php
// Lấy danh sách quyền của user
$userPerms = [];
if (isset($_SESSION['role']) && $_SESSION['role'] == 2) {
    if (isset($_SESSION['user_id'])) {
        // Luôn lấy quyền mới nhất từ DB để cập nhật tức thời
        $conn = new mysqli('localhost', 'root', '', 'cosmetics_db');
        if (!$conn->connect_error) {
            $stmt = $conn->prepare("SELECT permissions FROM users WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $_SESSION['permissions'] = $row['permissions'];
            }
            $stmt->close();
            $conn->close();
        }
    }
    $userPerms = isset($_SESSION['permissions']) ? explode(',', $_SESSION['permissions']) : [];
}

// Hàm kiểm tra hiển thị menu
function hasMenuPermission($module, $role, $perms) {
    if ($role == 1) return true; // Admin thấy tất cả
    if ($role == 2) return in_array($module, $perms); // Staff thấy module được cấp
    return false;
}
?>
<style>
    /* CSS Tùy chỉnh riêng cho Sidebar Admin */
    .admin-sidebar {
        background-color: #ffffff;
        box-shadow: 2px 0 20px rgba(0,0,0,0.04);
        min-height: 100vh;
        z-index: 10;
        position: relative;
    }
    .admin-brand {
        color: #be185d;
        font-weight: 900;
        font-size: 1.5rem;
        padding: 30px 20px;
        text-align: center;
        letter-spacing: 1px;
    }
    /* Đảm bảo sidebar không bị bóp méo (Fix kích thước cứng 260px thay vì % của col-md-2) */
    @media (min-width: 768px) {
        .admin-sidebar {
            flex: 0 0 260px !important;
            max-width: 260px !important;
        }
        .admin-sidebar + div {
            flex: 0 0 calc(100% - 260px) !important;
            max-width: calc(100% - 260px) !important;
        }
    }
    .admin-nav-link {
        color: #64748b;
        font-weight: 600;
        padding: 14px 20px;
        margin: 8px 15px;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        text-decoration: none;
    }
    .admin-nav-link i {
        width: 30px;
        font-size: 1.1rem;
    }
    .admin-nav-link:hover {
        background-color: #fce7f3;
        color: #be185d;
        transform: translateX(5px);
    }
    .admin-nav-link.active {
        background: linear-gradient(135deg, #be185d 0%, #db2777 100%);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(190, 24, 93, 0.25);
    }
    .logout-btn {
        transition: all 0.3s;
    }
    .logout-btn:hover {
        background-color: #fee2e2;
        color: #dc2626 !important;
        border-radius: 12px;
    }
</style>

<div class="col-md-2 p-0 admin-sidebar d-flex flex-column">
    <div class="admin-brand border-bottom">
        <i class="fas fa-spa me-2 text-pink"></i> GLOW <span class="text-dark">ADMIN</span>
    </div>
    
    <div class="p-2 mt-3 flex-grow-1">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (!isset($_GET['action']) || $_GET['action'] == 'index' || $_GET['action'] == 'revenueStats') ? 'active' : ''; ?>" href="index.php?controller=admin&action=index">
                    <i class="fas fa-chart-pie"></i> Tổng quan
                </a>
            </li>
            <?php if (hasMenuPermission('products', $_SESSION['role'] ?? 0, $userPerms)): ?>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && ($_GET['action'] == 'products' || strpos($_GET['action'], 'Product') !== false)) ? 'active' : ''; ?>" href="index.php?controller=admin&action=products">
                    <i class="fas fa-box-open"></i> Quản lý sản phẩm
                </a>
            </li>
            <?php endif; ?>
            <?php if (hasMenuPermission('banners', $_SESSION['role'] ?? 0, $userPerms)): ?>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'anner') !== false) ? 'active' : ''; ?>" href="index.php?controller=admin&action=banners">
                    <i class="fas fa-images"></i> Quản lý Banner
                </a>
            </li>
            <?php endif; ?>
            <?php if (hasMenuPermission('brands', $_SESSION['role'] ?? 0, $userPerms)): ?>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'rand') !== false) ? 'active' : ''; ?>" href="index.php?controller=admin&action=brands">
                    <i class="fas fa-gem"></i> Quản lý Thương hiệu
                </a>
            </li>
            <?php endif; ?>
            
            <!-- MENU NHÀ CUNG CẤP & KHO HÀNG -->
            <?php if (hasMenuPermission('products', $_SESSION['role'] ?? 0, $userPerms)): ?>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'upplier') !== false) ? 'active' : ''; ?>" href="index.php?controller=admin&action=suppliers">
                    <i class="fas fa-truck"></i> Quản lý Nhà cung cấp
                </a>
            </li>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && ($_GET['action'] == 'inventory' || $_GET['action'] == 'addStock')) ? 'active' : ''; ?>" href="index.php?controller=admin&action=inventory">
                    <i class="fas fa-boxes"></i> Quản lý Kho hàng
                </a>
            </li>
            <?php endif; ?>
            <?php if (hasMenuPermission('products', $_SESSION['role'] ?? 0, $userPerms)): ?>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && $_GET['action'] == 'promotions') ? 'active' : ''; ?>" href="index.php?controller=admin&action=promotions">
                    <i class="fas fa-tags"></i> Cấu hình Khuyến mãi
                </a>
            </li>
            <?php endif; ?>
            <?php if (hasMenuPermission('orders', $_SESSION['role'] ?? 0, $userPerms)): ?>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'order') !== false) ? 'active' : ''; ?>" href="index.php?controller=admin&action=orders">
                    <i class="fas fa-shopping-bag"></i> Quản lý đơn hàng 
                    <?php if(isset($newOrders) && $newOrders > 0): ?>
                        <span class="badge bg-danger ms-auto rounded-pill px-2 py-1"><?php echo $newOrders; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php endif; ?>
            
            <!-- MENU QUẢN LÝ BÀI VIẾT (TẠP CHÍ) -->
            <?php if (hasMenuPermission('posts', $_SESSION['role'] ?? 0, $userPerms)): ?>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'post') !== false) ? 'active' : ''; ?>" href="index.php?controller=admin&action=posts">
                    <i class="fas fa-newspaper"></i> Quản lý Bài viết
                </a>
            </li>
            <?php endif; ?>

            <!-- MENU QUẢN LÝ MÃ GIẢM GIÁ -->
            <?php if (hasMenuPermission('coupons', $_SESSION['role'] ?? 0, $userPerms)): ?>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'oupon') !== false) ? 'active' : ''; ?>" href="index.php?controller=admin&action=coupons">
                    <i class="fas fa-ticket-alt"></i> Mã Giảm Giá
                </a>
            </li>
            <?php endif; ?>

            <!-- MENU QUẢN LÝ HẠNG THÀNH VIÊN -->
            <?php if (hasMenuPermission('tiers', $_SESSION['role'] ?? 0, $userPerms)): ?>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'ier') !== false) ? 'active' : ''; ?>" href="index.php?controller=admin&action=tiers">
                    <i class="fas fa-crown"></i> Hạng Thành Viên
                </a>
            </li>
            <?php endif; ?>

            <!-- MENU QUẢN LÝ CHI NHÁNH -->
            <?php if (hasMenuPermission('stores', $_SESSION['role'] ?? 0, $userPerms)): ?>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'tore') !== false) ? 'active' : ''; ?>" href="index.php?controller=admin&action=stores">
                    <i class="fas fa-store"></i> Chi Nhánh
                </a>
            </li>
            <?php endif; ?>

            <!-- MENU PHÂN QUYỀN (CHỈ DÀNH CHO ADMIN) -->
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'user') !== false && $_GET['action'] != 'logout') ? 'active' : ''; ?>" href="index.php?controller=admin&action=users">
                    <i class="fas fa-users-cog"></i> Phân quyền & User
                </a>
            </li>
            <?php endif; ?>

            <!-- MENU CẤU HÌNH MENU TRANG WEB -->
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'enu') !== false) ? 'active' : ''; ?>" href="index.php?controller=admin&action=menus">
                    <i class="fas fa-list"></i> Quản lý Menu
                </a>
            </li>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'eview') !== false) ? 'active' : ''; ?>" href="index.php?controller=admin&action=reviews">
                    <i class="fas fa-star"></i> Quản lý Đánh giá
                </a>
            </li>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'chat') !== false) ? 'active' : ''; ?>" href="index.php?controller=admin&action=chat">
                    <i class="fas fa-comment-dots"></i> Hỗ trợ Khách hàng
                </a>
            </li>
            <?php endif; ?>

            <!-- MENU CẤU HÌNH HỆ THỐNG -->
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
            <li class="nav-item mt-3">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && $_GET['action'] == 'settings') ? 'active' : ''; ?>" href="index.php?controller=admin&action=settings" style="background-color: #f1f5f9;">
                    <i class="fas fa-cogs text-secondary"></i> Cấu hình Website
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
    
    <!-- Phần chân Sidebar -->
    <div class="p-3 mb-3 mx-2">
        <div class="bg-light rounded-4 p-3 text-center border">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['full_name'] ?? 'Admin'); ?>&background=fce7f3&color=be185d" class="rounded-circle mb-2 shadow-sm" width="50">
            <h6 class="fw-bold text-dark mb-1 text-truncate"><?php echo $_SESSION['full_name'] ?? 'Admin'; ?></h6>
            <p class="small text-muted mb-3"><?php echo (isset($_SESSION['role']) && $_SESSION['role'] == 1) ? 'Quản trị viên' : 'Nhân viên'; ?></p>
            <a href="index.php?controller=user&action=logout" class="d-block text-danger text-decoration-none fw-bold p-2 logout-btn">
                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
            </a>
        </div>
    </div>
</div>