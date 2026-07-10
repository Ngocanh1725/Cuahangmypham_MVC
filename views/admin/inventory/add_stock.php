<?php 
$pageTitle = "Nhập Hàng Mới - Glow Admin"; 
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Nhập hàng vào kho</h4>
                <a href="index.php?controller=admin&action=inventory" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
            </div>

            <?php if (!empty($message)) echo $message; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn sản phẩm <span class="text-danger">*</span></label>
                            <select name="product_id" class="form-select" required>
                                <option value="">-- Chọn sản phẩm cần nhập --</option>
                                <?php if(!empty($products)): foreach($products as $p): ?>
                                    <option value="<?php echo $p['id']; ?>">
                                        <?php echo htmlspecialchars($p['name']); ?> 
                                        (Tồn hiện tại: <?php echo $p['stock']; ?>)
                                    </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nhà cung cấp <span class="text-danger">*</span></label>
                                <select name="supplier_id" class="form-select" required>
                                    <option value="">-- Chọn nhà cung cấp --</option>
                                    <?php if(!empty($suppliers)): foreach($suppliers as $s): ?>
                                        <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số lượng nhập <span class="text-danger">*</span></label>
                                <input type="number" name="amount" class="form-control" min="1" value="10" required>
                            </div>
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success px-4"><i class="fas fa-plus"></i> Xác nhận nhập kho</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
