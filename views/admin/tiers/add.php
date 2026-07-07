<?php 
$pageTitle = "Thêm Hạng Thành Viên - Glow Admin"; 
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

            <div class="row justify-content-center">
                <div class="col-md-8 mt-4">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3 border-0">
                            <h4 class="fw-bold mb-0 text-dark">
                                <i class="fas fa-plus-circle me-2" style="color: #ff3b3b;"></i>Thêm Hạng Thành Viên Mới
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if(!empty($message)) echo $message; ?>
                            
                            <form method="POST" action="index.php?controller=admin&action=addTier">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tên hạng (Vd: Bronze, Silver, Gold, Platinum)</label>
                                    <input type="text" name="name" class="form-control" required placeholder="Nhập tên hạng...">
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Phần trăm giảm giá (%)</label>
                                        <input type="number" step="0.01" min="0" max="100" name="discount_percent" class="form-control" required placeholder="Vd: 5.0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Điểm tối thiểu cần đạt</label>
                                        <input type="number" name="min_points" class="form-control" required min="0" placeholder="Vd: 500">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mô tả ưu đãi</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Nhập mô tả các ưu đãi dành riêng cho hạng này..."></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Class Icon (FontAwesome)</label>
                                    <input type="text" name="icon_class" class="form-control" placeholder="Vd: fas fa-crown text-warning" value="fas fa-medal text-secondary">
                                    <div class="form-text">Bạn có thể dùng class của FontAwesome kèm class màu (vd: text-warning, text-secondary).</div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?controller=admin&action=tiers" class="btn btn-light px-4 rounded-pill">Hủy</a>
                                    <button type="submit" class="btn text-white px-4 rounded-pill fw-bold" style="background-color: #ff3b3b;">Lưu hạng mới</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
