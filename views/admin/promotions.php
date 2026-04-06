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
                <span class="text-muted">Nhập giá cũ lớn hơn giá bán để tự động tạo tem Sale</span>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                <div class="alert alert-success rounded-pill border-0 shadow-sm"><i class="fas fa-check-circle me-2"></i> Cập nhật giá thành công!</div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-4">Sản phẩm</th>
                                <th>Giá bán hiện tại</th>
                                <th>Giá Cũ (Để tạo % Sale)</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $row): ?>
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo htmlspecialchars($row['image']); ?>" width="45" height="45" class="rounded-3 me-3 shadow-sm" style="object-fit: cover;" onerror="this.src='https://via.placeholder.com/45'">
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($row['name']); ?></div>
                                    </div>
                                </td>
                                <td class="text-danger fw-bold fs-5"><?php echo number_format($row['price']); ?>đ</td>
                                <td>
                                    <!-- Form cập nhật giá cũ trực tiếp trên từng dòng -->
                                    <form action="index.php?controller=admin&action=savePromotion" method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <div class="input-group input-group-sm" style="width: 220px;">
                                            <input type="number" name="old_price" class="form-control fw-bold text-muted" value="<?php echo $row['old_price'] > 0 ? $row['old_price'] : ''; ?>" placeholder="Nhập giá cũ...">
                                            <button type="submit" class="btn btn-dark"><i class="fas fa-save"></i> Lưu</button>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <?php if($row['old_price'] > $row['price']): 
                                        $percent = round((($row['old_price'] - $row['price']) / $row['old_price']) * 100);
                                    ?>
                                        <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm"><i class="fas fa-fire me-1"></i> Sale <?php echo $percent; ?>%</span>
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