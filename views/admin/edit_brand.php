<?php 
$pageTitle = "Cập Nhật Thương Hiệu - Glow Admin"; 
$extraCSS = "<style>.img-preview { object-fit: contain; border-radius: 10px; border: 2px solid #eee; margin-top: 10px; }</style>";
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="row justify-content-center">
                <div class="col-md-8 mt-4">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3 border-0">
                            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-edit me-2 text-primary"></i>Sửa Thương Hiệu #<?php echo $brand['id']; ?></h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if(!empty($message)) echo $message; ?>
                            
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($brand['logo'] ?? ''); ?>">
                                <input type="hidden" name="current_banner" value="<?php echo htmlspecialchars($brand['banner'] ?? ''); ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tên Thương Hiệu</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($brand['name']); ?>" required>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Thay Logo (Bỏ qua nếu giữ nguyên)</label>
                                        <input type="file" name="logo" class="form-control" accept="image/*">
                                        <?php 
                                            $logoSrc = !empty($brand['logo']) ? $brand['logo'] : 'https://via.placeholder.com/100';
                                            echo "<div class='mt-2'><img src='$logoSrc' class='img-preview bg-white' style='width: 100px; height: 100px;' onerror=\"this.src='https://via.placeholder.com/100'\"></div>";
                                        ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Thay Banner (Bỏ qua nếu giữ nguyên)</label>
                                        <input type="file" name="banner" class="form-control" accept="image/*">
                                        <?php 
                                            $bannerSrc = !empty($brand['banner']) ? $brand['banner'] : 'https://via.placeholder.com/300x100';
                                            echo "<div class='mt-2'><img src='$bannerSrc' class='img-preview' style='width: 100%; height: 100px;' onerror=\"this.src='https://via.placeholder.com/300x100'\"></div>";
                                        ?>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Mô tả ngắn</label>
                                    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($brand['description'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?controller=admin&action=brands" class="btn btn-light px-4">Hủy</a>
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