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
                <a class="admin-nav-link <?php echo (!isset($_GET['action']) || $_GET['action'] == 'index') ? 'active' : ''; ?>" href="index.php?controller=admin&action=index">
                    <i class="fas fa-chart-pie"></i> Tổng quan
                </a>
            </li>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && ($_GET['action'] == 'products' || strpos($_GET['action'], 'Product') !== false)) ? 'active' : ''; ?>" href="index.php?controller=admin&action=products">
                    <i class="fas fa-box-open"></i> Quản lý sản phẩm
                </a>
            </li>
            <!-- ĐÃ THÊM LINK VÀO PHẦN CẤU HÌNH KHUYẾN MÃI -->
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && $_GET['action'] == 'promotions') ? 'active' : ''; ?>" href="index.php?controller=admin&action=promotions">
                    <i class="fas fa-tags"></i> Cấu hình Khuyến mãi
                </a>
            </li>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'order') !== false) ? 'active' : ''; ?>" href="index.php?controller=admin&action=orders">
                    <i class="fas fa-shopping-bag"></i> Quản lý đơn hàng 
                    <?php if(isset($newOrders) && $newOrders > 0): ?>
                        <span class="badge bg-danger ms-auto rounded-pill px-2 py-1"><?php echo $newOrders; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="admin-nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'user') !== false && $_GET['action'] != 'logout') ? 'active' : ''; ?>" href="index.php?controller=admin&action=users">
                    <i class="fas fa-users-cog"></i> Phân quyền & User
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Phần chân Sidebar -->
    <div class="p-3 mb-3 mx-2">
        <div class="bg-light rounded-4 p-3 text-center border">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['full_name'] ?? 'Admin'); ?>&background=fce7f3&color=be185d" class="rounded-circle mb-2 shadow-sm" width="50">
            <h6 class="fw-bold text-dark mb-1 text-truncate"><?php echo $_SESSION['full_name'] ?? 'Admin'; ?></h6>
            <p class="small text-muted mb-3">Quản trị viên</p>
            <a href="index.php?controller=user&action=logout" class="d-block text-danger text-decoration-none fw-bold p-2 logout-btn">
                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
            </a>
        </div>
    </div>
</div>