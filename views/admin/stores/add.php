<?php 
$pageTitle = "Thêm Chi Nhánh - Glow Admin"; 
include 'views/layout/header.php'; 
?>
<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="row justify-content-center">
                <div class="col-md-7 mt-4">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3 border-0">
                            <h4 class="fw-bold mb-0"><i class="fas fa-plus-circle me-2 text-success"></i>Thêm Chi Nhánh Mới</h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if(!empty($message)) echo $message; ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tên chi nhánh <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required placeholder="VD: Glow Hà Đông">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Địa chỉ đầy đủ <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="2" required placeholder="Số nhà, đường, phường/xã, quận/huyện"></textarea>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Thành phố</label>
                                        <input type="text" name="city" class="form-control" placeholder="Hà Nội">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Số điện thoại</label>
                                        <input type="text" name="phone" class="form-control" placeholder="024 xxxx xxxx">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Giờ mở cửa</label>
                                        <input type="text" name="open_hours" class="form-control" value="08:00 - 21:30">
                                    </div>
                                </div>
                                <div class="mb-4 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" checked>
                                    <label class="form-check-label fw-bold ms-2" for="isActive">Đang hoạt động</label>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?controller=admin&action=stores" class="btn btn-light px-4">Hủy</a>
                                    <button type="submit" class="btn btn-brand px-4">Thêm chi nhánh</button>
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
