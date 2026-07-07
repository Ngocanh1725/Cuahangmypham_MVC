<?php 
$pageTitle = "Thêm Mã Giảm Giá - Glow Admin"; 
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
                            <h4 class="fw-bold mb-0"><i class="fas fa-plus-circle me-2 text-warning"></i>Thêm Mã Giảm Giá Mới</h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if(!empty($message)) echo $message; ?>
                            <form method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Mã Code <span class="text-danger">*</span></label>
                                        <input type="text" name="code" class="form-control text-uppercase" required placeholder="VD: GLOW50K" style="letter-spacing:2px; font-weight:700;">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Loại giảm giá</label>
                                        <select name="type" class="form-select" id="couponType">
                                            <option value="percent">Phần trăm (%)</option>
                                            <option value="fixed">Số tiền cố định (VND)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Giá trị giảm <span class="text-danger">*</span></label>
                                        <input type="number" name="discount_value" class="form-control" required step="0.01" placeholder="10">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Đơn tối thiểu (VND)</label>
                                        <input type="number" name="min_order_value" class="form-control" value="0" placeholder="200000">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Giảm tối đa (VND)</label>
                                        <input type="number" name="max_discount" class="form-control" placeholder="50000">
                                        <small class="text-muted">Chỉ cho loại %. Bỏ trống = không giới hạn</small>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Số lượt sử dụng tối đa</label>
                                        <input type="number" name="usage_limit" class="form-control" placeholder="Bỏ trống = Không giới hạn">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Ngày bắt đầu</label>
                                        <input type="datetime-local" name="start_date" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Ngày hết hạn</label>
                                        <input type="datetime-local" name="end_date" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mô tả (hiển thị cho khách)</label>
                                    <input type="text" name="description" class="form-control" placeholder="VD: Giảm 10% cho đơn từ 200K">
                                </div>
                                <div class="mb-4 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" checked>
                                    <label class="form-check-label fw-bold ms-2" for="isActive">Kích hoạt ngay</label>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?controller=admin&action=coupons" class="btn btn-light px-4">Hủy</a>
                                    <button type="submit" class="btn btn-brand px-4">Tạo mã giảm giá</button>
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
