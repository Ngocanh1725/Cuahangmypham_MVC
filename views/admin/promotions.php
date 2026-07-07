<?php 
$pageTitle = "Cấu hình Khuyến mãi - Glow Admin"; 
include 'views/layout/header.php'; 
?>
<div class="container-fluid p-0" style="background-color: #f8fafc;">
    <div class="row g-0">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 p-md-5 min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold m-0"><i class="fas fa-tags text-danger me-2"></i> Cấu hình Giá & Khuyến mãi</h3>
                <span class="text-muted">Nhập % Sale để tự động cập nhật giá cũ</span>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                <div class="alert alert-success rounded-pill border-0 shadow-sm"><i class="fas fa-check-circle me-2"></i> Cập nhật giá thành công!</div>
            <?php endif; ?>

            <!-- Filter form -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-3">
                    <form action="index.php" method="GET" class="row g-3 align-items-center">
                        <input type="hidden" name="controller" value="admin">
                        <input type="hidden" name="action" value="promotions">
                        
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Tìm tên hoặc ID sản phẩm..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        </div>
                        
                        <div class="col-md-3">
                            <select name="category_id" class="form-select">
                                <option value="">Tất cả danh mục</option>
                                <?php if(isset($categories)): foreach($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo (isset($category_id) && $category_id == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <select name="brand_id" class="form-select">
                                <option value="">Tất cả thương hiệu</option>
                                <?php if(isset($brands)): foreach($brands as $brand): ?>
                                    <option value="<?php echo $brand['id']; ?>" <?php echo (isset($brand_id) && $brand_id == $brand['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($brand['name']); ?>
                                    </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-dark"><i class="fas fa-filter"></i> Lọc</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-4">Sản phẩm</th>
                                <th>Giá bán hiện tại</th>
                                <th>% Khuyến mãi (Ví dụ: 20)</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $row): ?>
                            <?php 
                                $current_percent = '';
                                if ($row['old_price'] > $row['price']) {
                                    $current_percent = round((($row['old_price'] - $row['price']) / $row['old_price']) * 100);
                                }
                            ?>
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo htmlspecialchars($row['image']); ?>" width="45" height="45" class="rounded-3 me-3 shadow-sm" style="object-fit: cover;" onerror="this.src='https://via.placeholder.com/45'">
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($row['name']); ?></div>
                                    </div>
                                </td>
                                <td class="text-danger fw-bold fs-5"><?php echo number_format($row['price']); ?>đ</td>
                                <td>
                                    <!-- Form cập nhật % Sale và Flash Sale trực tiếp trên từng dòng -->
                                    <form action="index.php?controller=admin&action=savePromotion" method="POST" class="d-flex align-items-center gap-3">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="current_price" value="<?php echo $row['price']; ?>">
                                        
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="number" min="0" max="99" name="discount_percent" class="form-control fw-bold text-muted" value="<?php echo $current_percent; ?>" placeholder="VD: 25">
                                            <span class="input-group-text bg-light">%</span>
                                        </div>

                                        <div class="form-check form-switch m-0" title="Hiển thị ở Flash Sale trang chủ">
                                            <input class="form-check-input" type="checkbox" name="is_flash_sale" value="1" id="fs_<?php echo $row['id']; ?>" <?php echo (isset($row['is_flash_sale']) && $row['is_flash_sale']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label small" for="fs_<?php echo $row['id']; ?>"><i class="fas fa-bolt text-warning"></i> Flash Sale</label>
                                        </div>

                                        <button type="submit" class="btn btn-dark btn-sm"><i class="fas fa-save"></i> Lưu</button>
                                    </form>
                                </td>
                                <td>
                                    <?php if($current_percent !== '' && $current_percent > 0): ?>
                                        <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm"><i class="fas fa-fire me-1"></i> Sale <?php echo $current_percent; ?>%</span>
                                        <div class="small text-muted mt-1">Giá gốc: <del><?php echo number_format($row['old_price']); ?>đ</del></div>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">Bình thường</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'views/layout/footer.php'; ?>