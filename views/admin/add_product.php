<?php 
$pageTitle = "Thêm Sản Phẩm (MVC) - Glow Admin"; 
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
                            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-plus-circle me-2 text-danger"></i>Thêm Món Mới</h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if(!empty($message)) echo $message; ?>
                            
                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tên sản phẩm</label>
                                    <input type="text" name="name" class="form-control" required placeholder="Nhập tên mỹ phẩm...">
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Danh mục</label>
                                        <select name="category" class="form-select">
                                            <option value="Chăm sóc da">Chăm sóc da</option>
                                            <option value="Trang điểm">Trang điểm</option>
                                            <option value="Nước hoa">Nước hoa</option>
                                            <option value="Cơ thể & Tóc">Cơ thể & Tóc</option>
                                            <option value="Son môi">Son môi</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Giá bán (VNĐ)</label>
                                        <input type="number" name="price" class="form-control" required placeholder="Ví dụ: 250000">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Hình ảnh sản phẩm</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <div class="form-text">Cho phép up file ảnh trực tiếp lên hệ thống.</div>
                                </div>
                                
                                <!-- HÀNG CHỨA TỒN KHO VÀ TRẠNG THÁI (MỚI) -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Số lượng tồn kho</label>
                                        <input type="number" name="stock" class="form-control" required min="0" value="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Trạng thái hiển thị</label>
                                        <select name="status" class="form-select">
                                            <option value="1">Còn hàng</option>
                                            <option value="0">Hết hàng</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?controller=admin&action=products" class="btn btn-light px-4">Hủy</a>
                                    <button type="submit" class="btn btn-brand px-4">Lưu sản phẩm</button>
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