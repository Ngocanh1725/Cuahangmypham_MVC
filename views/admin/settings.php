<?php 
$pageTitle = "Cấu hình Website - Glow Admin"; 
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <!-- Header Bar -->
            <div class="d-flex justify-content-end align-items-center mb-4 gap-3 bg-white p-3 rounded-4 shadow-sm border">
                <span class="fw-bold text-dark me-auto ps-2">
                    <i class="fas fa-cogs text-secondary me-2 fs-5 align-middle"></i>
                    Cấu hình hệ thống
                </span>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8 mt-2">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3 border-0 border-bottom">
                            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-sliders-h me-2 text-primary"></i>Thiết lập chung</h4>
                            <p class="text-muted small mb-0 mt-1">Thay đổi các thông tin cơ bản hiển thị trên Website cho khách hàng.</p>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <?php if(!empty($message)) echo $message; ?>
                            
                            <form method="POST" action="index.php?controller=admin&action=settings">
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark">Tên Website (Brand Name)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-globe"></i></span>
                                        <input type="text" name="site_name" class="form-control" value="<?php echo htmlspecialchars($settings['site_name']['setting_value'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-text">Tên thương hiệu sẽ hiển thị trên thanh tiêu đề trình duyệt.</div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark">Hotline Chăm sóc khách hàng</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-phone-alt"></i></span>
                                            <input type="text" name="hotline" class="form-control" value="<?php echo htmlspecialchars($settings['hotline']['setting_value'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark">Email liên hệ</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($settings['email']['setting_value'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label fw-bold text-dark">Địa chỉ Trụ sở chính (Footer)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($settings['address']['setting_value'] ?? ''); ?>">
                                    </div>
                                </div>

                                <hr class="my-4 text-muted">
                                
                                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-shopping-cart me-2 text-primary"></i>Cấu hình Mua hàng & Vận chuyển</h5>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-dark">Phí vận chuyển mặc định</label>
                                        <div class="input-group">
                                            <input type="number" name="shipping_fee_default" class="form-control" value="<?php echo htmlspecialchars($settings['shipping_fee_default']['setting_value'] ?? '30000'); ?>">
                                            <span class="input-group-text bg-light">VND</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-dark">Miễn phí ship cho đơn từ</label>
                                        <div class="input-group">
                                            <input type="number" name="free_shipping_min" class="form-control" value="<?php echo htmlspecialchars($settings['free_shipping_min']['setting_value'] ?? '500000'); ?>">
                                            <span class="input-group-text bg-light">VND</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-dark">Điểm thưởng mỗi 1.000đ</label>
                                        <div class="input-group">
                                            <input type="number" step="0.1" name="points_per_1000" class="form-control" value="<?php echo htmlspecialchars($settings['points_per_1000']['setting_value'] ?? '1'); ?>">
                                            <span class="input-group-text bg-light">Điểm</span>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4 text-muted">
                                
                                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Cấu hình Thuế VAT</h5>
                                <div class="row mb-4 align-items-center">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch fs-5">
                                            <input class="form-check-input" type="checkbox" name="vat_enabled" value="1" id="vatEnabled" <?php echo (isset($settings['vat_enabled']['setting_value']) && $settings['vat_enabled']['setting_value'] == '1') ? 'checked' : ''; ?>>
                                            <label class="form-check-label fw-bold ms-2 fs-6" for="vatEnabled">Bật tính thuế VAT</label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label fw-bold text-dark">% Thuế VAT áp dụng</label>
                                        <div class="input-group w-50">
                                            <input type="number" name="vat_percent" class="form-control" value="<?php echo htmlspecialchars($settings['vat_percent']['setting_value'] ?? '10'); ?>">
                                            <span class="input-group-text bg-light">%</span>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4 text-muted">
                                
                                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-bolt me-2 text-warning"></i>Cấu hình Flash Sale (Trang Chủ)</h5>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark">Thời gian kết thúc Flash Sale</label>
                                        <div class="input-group">
                                            <input type="datetime-local" name="flash_sale_end" class="form-control" value="<?php echo htmlspecialchars($settings['flash_sale_end']['setting_value'] ?? ''); ?>">
                                        </div>
                                        <div class="form-text">Ví dụ: Để đếm ngược đến hết ngày hôm nay. Nếu để trống sẽ ẩn bộ đếm.</div>
                                    </div>
                                </div>

                                <hr class="my-4 text-muted">
                                
                                <div class="d-flex justify-content-end gap-3 mt-4">
                                    <button type="reset" class="btn btn-light px-4 border fw-bold rounded-pill">Hủy bỏ</button>
                                    <button type="submit" class="btn btn-brand px-5 py-2 fw-bold shadow-sm rounded-pill"><i class="fas fa-save me-2"></i>Lưu cấu hình</button>
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