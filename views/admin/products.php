<?php 
$pageTitle = "Quản lý Sản phẩm - Glow Admin"; 
$extraCSS = "<style>.product-img-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }</style>";
include 'views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'views/admin/includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                <div>
                    <h3 class="fw-bold text-dark">Danh sách Mỹ Phẩm</h3>
                    <p class="text-muted">Quản lý kho hàng và giá cả</p>
                </div>
                <!-- Nút thêm sản phẩm trỏ về MVC -->
                <a href="index.php?controller=admin&action=addProduct" class="btn btn-brand shadow-sm">
                    <i class="fas fa-plus me-2"></i> Thêm sản phẩm
                </a>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 table-custom">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">ID</th>
                                <th class="py-3">Hình ảnh</th>
                                <th class="py-3">Tên sản phẩm</th>
                                <th class="py-3">Danh mục</th>
                                <th class="py-3">Giá bán</th>
                                <!-- CỘT TỒN KHO MỚI -->
                                <th class="py-3 text-center">Tồn kho</th>
                                <th class="py-3">Trạng thái</th>
                                <th class="text-end pe-4 py-3">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($products)): ?>
                                <?php foreach($products as $row): 
                                    // --- XỬ LÝ DỮ LIỆU ---
                                    $id = $row["id"];
                                    $name = htmlspecialchars($row["name"]);
                                    $category = isset($row["category"]) ? htmlspecialchars($row["category"]) : "Chưa phân loại";
                                    $price = isset($row["price"]) ? number_format($row["price"]) : "0";
                                    $status = isset($row["status"]) ? $row["status"] : 1;
                                    
                                    // --- XỬ LÝ HIỂN THỊ ẢNH ---
                                    $imgSrc = isset($row['image']) ? $row['image'] : '';
                                    $displayImg = "https://via.placeholder.com/50?text=No+Img";

                                    if (!empty($imgSrc)) {
                                        if (strpos($imgSrc, 'http') !== false) {
                                            $displayImg = $imgSrc;
                                        } else {
                                            $displayImg = $imgSrc;
                                        }
                                    }

                                    $statusBadge = ($status == 1) 
                                        ? '<span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Còn hàng</span>' 
                                        : '<span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">Hết hàng</span>';
                                ?>
                                    <tr>
                                        <td class='ps-4 fw-bold text-muted'>#<?php echo $id; ?></td>
                                        <td><img src='<?php echo $displayImg; ?>' class='product-img-thumb' alt='img'></td>
                                        <td><div class='fw-bold text-dark'><?php echo $name; ?></div></td>
                                        <td><span class='badge bg-light text-dark border'><?php echo $category; ?></span></td>
                                        <td class='fw-bold' style='color: var(--brand-dark)'><?php echo $price; ?>đ</td>
                                        <!-- HIỂN THỊ DỮ LIỆU TỒN KHO -->
                                        <td class='fw-bold text-center text-primary'><?php echo isset($row['stock']) ? $row['stock'] : 0; ?></td>
                                        <td><?php echo $statusBadge; ?></td>
                                        <td class='text-end pe-4'>
                                            <a href='index.php?controller=admin&action=editProduct&id=<?php echo $id; ?>' class='btn btn-sm btn-light text-primary me-2 rounded-circle' title='Sửa'>
                                                <i class='fas fa-edit'></i>
                                            </a>
                                            <a href='index.php?controller=admin&action=deleteProduct&id=<?php echo $id; ?>' onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" class='btn btn-sm btn-light text-danger rounded-circle' title='Xóa'>
                                                <i class='fas fa-trash'></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='8' class='text-center py-5 text-muted'>Chưa có sản phẩm nào trong kho</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>