<?php 
$pageTitle = "Sửa Sản Phẩm (MVC) - Glow Admin"; 
$extraCSS = "<style>.img-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 10px; border: 2px solid #eee; margin-top: 10px; }</style>";
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <!-- Thanh Header bổ sung Đăng xuất / Về trang chủ trực tiếp -->
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
                            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-edit me-2 text-primary"></i>Sửa Sản Phẩm #<?php echo $product['id']; ?></h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if(!empty($message)) echo $message; ?>
                            
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($product['image'] ?? ''); ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tên sản phẩm</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Danh mục</label>
                                        <select name="category" class="form-select">
                                            <option value="Chăm sóc da" <?php echo ($product['category'] == 'Chăm sóc da') ? 'selected' : ''; ?>>Chăm sóc da</option>
                                            <option value="Trang điểm" <?php echo ($product['category'] == 'Trang điểm') ? 'selected' : ''; ?>>Trang điểm</option>
                                            <option value="Nước hoa" <?php echo ($product['category'] == 'Nước hoa') ? 'selected' : ''; ?>>Nước hoa</option>
                                            <option value="Cơ thể & Tóc" <?php echo ($product['category'] == 'Cơ thể & Tóc') ? 'selected' : ''; ?>>Cơ thể & Tóc</option>
                                            <option value="Son môi" <?php echo ($product['category'] == 'Son môi') ? 'selected' : ''; ?>>Son môi</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Thương hiệu</label>
                                        <select name="brand_id" class="form-select">
                                            <option value="">-- Không chọn --</option>
                                            <?php if(!empty($brandsList)): ?>
                                                <?php foreach($brandsList as $b): ?>
                                                    <option value="<?php echo $b['id']; ?>" <?php echo (isset($product['brand_id']) && $product['brand_id'] == $b['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($b['name']); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Giá bán (VNĐ)</label>
                                        <input type="number" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Hình ảnh mới (Tùy chọn)</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <div class="form-text text-muted">Bỏ qua nếu muốn giữ nguyên ảnh cũ.</div>
                                    
                                    <?php 
                                        $imgSrc = !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/100';
                                        echo "<div class='mt-2'>Ảnh hiện tại:<br><img src='$imgSrc' class='img-preview'></div>";
                                    ?>
                                </div>

                                <!-- HÀNG CHỨA TỒN KHO VÀ TRẠNG THÁI (MỚI) -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Số lượng tồn kho</label>
                                        <input type="number" name="stock" class="form-control" required min="0" value="<?php echo isset($product['stock']) ? intval($product['stock']) : 0; ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Trạng thái hiển thị</label>
                                        <select name="status" class="form-select">
                                            <option value="1" <?php if($product['status'] == 1) echo 'selected'; ?>>Còn hàng</option>
                                            <option value="0" <?php if($product['status'] == 0) echo 'selected'; ?>>Hết hàng</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?controller=admin&action=products" class="btn btn-light px-4">Hủy</a>
                                    <button type="submit" class="btn btn-brand px-4">Lưu cập nhật</button>
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