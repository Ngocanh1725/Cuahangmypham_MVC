<?php 
$pageTitle = "Quản Lý Hạng Thành Viên - Glow Admin"; 
// Thêm style riêng cho các màu hạng
$extraCSS = "
<style>
    .tier-card { border: 0; border-radius: 16px; transition: transform 0.2s, box-shadow 0.2s; background: #fff; height: 100%; display: flex; flex-direction: column; }
    .tier-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
    .tier-icon-wrapper { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 16px; background: #f8f9fa; }
    .tier-badge { background: #1a1a1a; color: #fff; padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 13px; display: inline-block; margin-bottom: 16px; }
    
    /* Màu sắc đặc trưng cho từng hạng (dựa vào tên) */
    .text-tier-bronze { color: #cd7f32 !important; }
    .bg-tier-bronze { background-color: rgba(205, 127, 50, 0.1) !important; }
    
    .text-tier-silver { color: #9e9e9e !important; }
    .bg-tier-silver { background-color: rgba(158, 158, 158, 0.1) !important; }
    
    .text-tier-gold { color: #f39c12 !important; }
    .bg-tier-gold { background-color: rgba(243, 156, 18, 0.1) !important; }
    
    .text-tier-platinum { color: #00bcd4 !important; }
    .bg-tier-platinum { background-color: rgba(0, 188, 212, 0.1) !important; }
    
    .text-tier-default { color: #ff3b3b !important; }
    .bg-tier-default { background-color: rgba(255, 59, 59, 0.1) !important; }
</style>
";
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <!-- Thanh Header -->
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

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm">
                <div>
                    <h3 class="fw-bold text-dark mb-1">
                        <i class="fas fa-crown text-warning me-2"></i>Hạng Thành Viên
                    </h3>
                    <p class="text-muted mb-0">Thiết lập các cấp bậc & ưu đãi cho khách hàng trung thành</p>
                </div>
                <a href="index.php?controller=admin&action=addTier" class="btn btn-brand rounded-pill px-4 py-2 fw-bold shadow-sm" style="background-color: #ff3b3b; color: #fff; border: none;">
                    <i class="fas fa-plus me-2"></i>Thêm hạng mới
                </a>
            </div>

            <?php if(isset($_GET['msg'])): ?>
                <?php if($_GET['msg'] == 'added'): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-2"></i> Thêm hạng thành viên thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif($_GET['msg'] == 'updated'): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-2"></i> Cập nhật thông tin thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Tiers Grid -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <?php if(!empty($tiers)): ?>
                    <?php foreach($tiers as $tier): 
                        // Logic lấy class màu sắc dựa trên tên
                        $tierNameLower = strtolower($tier['name']);
                        $textClass = 'text-tier-default';
                        $bgClass = 'bg-tier-default';
                        
                        if(strpos($tierNameLower, 'bronze') !== false) {
                            $textClass = 'text-tier-bronze';
                            $bgClass = 'bg-tier-bronze';
                        } elseif(strpos($tierNameLower, 'silver') !== false) {
                            $textClass = 'text-tier-silver';
                            $bgClass = 'bg-tier-silver';
                        } elseif(strpos($tierNameLower, 'gold') !== false) {
                            $textClass = 'text-tier-gold';
                            $bgClass = 'bg-tier-gold';
                        } elseif(strpos($tierNameLower, 'platinum') !== false || strpos($tierNameLower, 'diamond') !== false) {
                            $textClass = 'text-tier-platinum';
                            $bgClass = 'bg-tier-platinum';
                        }
                    ?>
                    <div class="col">
                        <div class="card tier-card shadow-sm p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="tier-icon-wrapper <?php echo $bgClass; ?> <?php echo $textClass; ?>">
                                    <i class="<?php echo !empty($tier['icon_class']) ? htmlspecialchars($tier['icon_class']) : 'fas fa-medal'; ?>"></i>
                                </div>
                                <!-- Action Buttons -->
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle shadow-sm border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 32px; height: 32px; padding: 0;">
                                        <i class="fas fa-ellipsis-v text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                        <li><a class="dropdown-item py-2 fw-medium text-primary" href="index.php?controller=admin&action=editTier&id=<?php echo $tier['id']; ?>"><i class="fas fa-edit me-2"></i>Sửa hạng</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item py-2 fw-medium text-danger" href="index.php?controller=admin&action=deleteTier&id=<?php echo $tier['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa hạng này? Các khách hàng ở hạng này có thể bị ảnh hưởng.');">
                                                <i class="fas fa-trash-alt me-2"></i>Xóa hạng
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <h4 class="fw-bold mb-3 <?php echo $textClass; ?>"><?php echo htmlspecialchars($tier['name']); ?></h4>
                            
                            <div class="tier-badge">
                                Giảm <?php echo floatval($tier['discount_percent']); ?>%
                            </div>
                            
                            <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">
                                <?php echo htmlspecialchars($tier['description']); ?>
                            </p>
                            
                            <div class="mt-auto pt-3 border-top">
                                <div class="d-flex align-items-center text-dark fw-bold">
                                    <i class="fas fa-coins text-warning me-2"></i> 
                                    Điểm tối thiểu: <span class="ms-1 fs-5"><?php echo number_format($tier['min_points'], 0, ',', '.'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="bg-white p-5 rounded-4 shadow-sm text-center">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" alt="No tiers" width="120" class="mb-3 opacity-50">
                            <h5 class="text-muted fw-bold">Chưa có hạng thành viên nào</h5>
                            <p class="text-muted mb-4">Hãy thêm hạng thành viên đầu tiên để tạo chương trình ưu đãi cho khách hàng.</p>
                            <a href="index.php?controller=admin&action=addTier" class="btn btn-brand rounded-pill px-4 py-2 fw-bold shadow-sm" style="background-color: #ff3b3b; color: #fff; border: none;">
                                <i class="fas fa-plus me-2"></i>Thêm hạng mới
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
