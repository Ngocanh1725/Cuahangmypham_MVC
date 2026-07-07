<?php 
$pageTitle = "Thêm Thương Hiệu - Glow Admin"; 
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
                            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-plus-circle me-2 text-primary"></i>Thêm Thương Hiệu Mới</h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if(!empty($message)) echo $message; ?>
                            
                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tên Thương Hiệu <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required placeholder="VD: L'Oreal, MAC...">
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Logo (Ảnh vuông)</label>
                                        <input type="file" name="logo" class="form-control" accept="image/*">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Banner (Ảnh dài/ngang)</label>
                                        <input type="file" name="banner" class="form-control" accept="image/*">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Mô tả ngắn</label>
                                    <textarea name="description" class="form-control" rows="4" placeholder="Nhập giới thiệu về thương hiệu..."></textarea>
                                </div>
                                
                                <div class="mb-4 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_home" name="is_home" value="1" checked>
                                    <label class="form-check-label fw-bold ms-2" for="is_home">Hiển thị ở dải trang chủ</label>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?controller=admin&action=brands" class="btn btn-light px-4">Hủy</a>
                                    <button type="submit" class="btn btn-brand px-4">Lưu thương hiệu</button>
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