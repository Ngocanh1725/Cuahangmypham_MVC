<?php 
$pageTitle = "Sửa Mã Giảm Giá - Glow Admin"; 
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
                            <h4 class="fw-bold mb-0"><i class="fas fa-edit me-2 text-primary"></i>Sửa Mã: <span class="text-warning"><?= htmlspecialchars($coupon['code']) ?></span></h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if(!empty($message)) echo $message; ?>
                            <form method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Mã Code <span class="text-danger">*</span></label>
                                        <input type="text" name="code" class="form-control text-uppercase" required value="<?= htmlspecialchars($coupon['code']) ?>" style="letter-spacing:2px; font-weight:700;">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Loại giảm giá</label>
                                        <select name="type" class="form-select">
                                            <option value="percent" <?= $coupon['type']=='percent'?'selected':'' ?>>Phần trăm (%)</option>
                                            <option value="fixed" <?= $coupon['type']=='fixed'?'selected':'' ?>>Số tiền cố định (VND)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Giá trị giảm <span class="text-danger">*</span></label>
                                        <input type="number" name="discount_value" class="form-control" required step="0.01" value="<?= $coupon['discount_value'] ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Đơn tối thiểu (VND)</label>
                                        <input type="number" name="min_order_value" class="form-control" value="<?= $coupon['min_order_value'] ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Giảm tối đa (VND)</label>
                                        <input type="number" name="max_discount" class="form-control" value="<?= $coupon['max_discount'] ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Lượt sử dụng tối đa</label>
                                        <input type="number" name="usage_limit" class="form-control" value="<?= $coupon['usage_limit'] ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Ngày bắt đầu</label>
                                        <input type="datetime-local" name="start_date" class="form-control" value="<?= $coupon['start_date'] ? date('Y-m-d\TH:i', strtotime($coupon['start_date'])) : '' ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Ngày hết hạn</label>
                                        <input type="datetime-local" name="end_date" class="form-control" value="<?= $coupon['end_date'] ? date('Y-m-d\TH:i', strtotime($coupon['end_date'])) : '' ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mô tả</label>
                                    <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($coupon['description'] ?? '') ?>">
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" <?= $coupon['is_active'] ? 'checked' : '' ?>>
                                            <label class="form-check-label fw-bold ms-2" for="isActive">Kích hoạt</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <small class="text-muted">Đã sử dụng: <strong><?= $coupon['used_count'] ?></strong> lượt</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?controller=admin&action=coupons" class="btn btn-light px-4">Hủy</a>
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
