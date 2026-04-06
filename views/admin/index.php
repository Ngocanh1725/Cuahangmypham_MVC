<?php 
$pageTitle = "Tổng quan - Glow Admin"; 
include 'views/layout/header.php'; 
?>
<style>
    /* Nền trang Admin màu xám xanh nhạt cho cảm giác sạch sẽ */
    .admin-main-bg { background-color: #f8fafc; }
    
    /* Hiệu ứng cho các Card thống kê */
    .stat-card {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s;
        border-radius: 20px;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.06) !important;
    }
    
    /* Box chứa Icon */
    .icon-box {
        width: 65px;
        height: 65px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }
    
    /* Bảng màu Pastel cho Icon */
    .bg-pink-light { background-color: #fce7f3; color: #be185d; }
    .bg-orange-light { background-color: #ffedd5; color: #ea580c; }
    .bg-blue-light { background-color: #e0f2fe; color: #0284c7; }
    
    /* Banner Gradient */
    .welcome-banner {
        background: linear-gradient(135deg, #be185d 0%, #db2777 100%);
        color: white;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(190, 24, 93, 0.2);
    }
</style>

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar -->
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="col-md-10 p-4 p-md-5 admin-main-bg min-vh-100">
            
            <!-- Welcome Banner -->
            <div class="welcome-banner p-4 p-md-5 mb-5 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div class="mb-4 mb-md-0">
                    <span class="badge bg-white text-danger mb-2 px-3 py-2 rounded-pill fw-bold shadow-sm">Bảng điều khiển</span>
                    <h2 class="fw-bold mb-2">Xin chào, <?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Admin'; ?>! 👋</h2>
                    <p class="mb-0 fs-5 opacity-75">Hôm nay là một ngày tuyệt vời để bứt phá doanh thu.</p>
                </div>
                <div class="d-flex gap-3">
                    <a href="index.php" class="btn btn-light rounded-pill px-4 py-2 fw-bold shadow-sm text-decoration-none" style="color: #be185d;">
                        <i class="fas fa-store me-2"></i> Xem cửa hàng
                    </a>
                </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark m-0">Tổng quan hệ thống</h4>
                <div class="text-muted small"><i class="fas fa-calendar-alt me-1"></i> Dữ liệu cập nhật theo thời gian thực</div>
            </div>
            
            <!-- Cards Thống kê -->
            <div class="row g-4 mb-5">
                
                <!-- Doanh thu -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 1px;">Tổng doanh thu</h6>
                                    <h2 class="fw-black mb-0" style="color: #be185d; font-weight: 900;"><?php echo number_format($totalRevenue); ?>đ</h2>
                                </div>
                                <div class="icon-box bg-pink-light">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </div>
                            <div class="mt-4">
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1"><i class="fas fa-arrow-up me-1"></i> Tăng 15%</span> 
                                <span class="text-muted small ms-1">so với tháng trước</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Đơn hàng -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 1px;">Đơn chờ xử lý</h6>
                                    <h2 class="fw-black mb-0 text-dark" style="font-weight: 900;"><?php echo $newOrders; ?></h2>
                                </div>
                                <div class="icon-box bg-blue-light">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="mt-4">
                                <?php if($newOrders > 0): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-1"><i class="fas fa-clock me-1"></i> Cần xử lý ngay</span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1"><i class="fas fa-check me-1"></i> Đã xử lý hết</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sản phẩm -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 1px;">Tổng sản phẩm</h6>
                                    <h2 class="fw-black mb-0 text-dark" style="font-weight: 900;"><?php echo $totalProducts; ?></h2>
                                </div>
                                <div class="icon-box bg-orange-light">
                                    <i class="fas fa-box-open"></i>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="index.php?controller=admin&action=products" class="text-decoration-none fw-bold" style="color: #ea580c; font-size: 0.9rem;">
                                    Quản lý kho hàng <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <!-- Quick Actions / Hướng dẫn -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4"><i class="fas fa-bolt text-warning me-2"></i> Thao tác nhanh</h5>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="index.php?controller=admin&action=addProduct" class="btn btn-outline-danger rounded-pill px-4 py-2 fw-bold">
                                    <i class="fas fa-plus me-2"></i> Thêm mỹ phẩm mới
                                </a>
                                <a href="index.php?controller=admin&action=orders" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold">
                                    <i class="fas fa-truck me-2"></i> Xử lý giao hàng
                                </a>
                                <a href="index.php?controller=admin&action=addUser" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold">
                                    <i class="fas fa-user-plus me-2"></i> Cấp tài khoản nhân viên
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>