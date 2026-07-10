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
            
            <!-- FORM TÌM KIẾM & LỌC -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-body p-3">
                    <form method="GET" action="index.php" class="row g-3 align-items-center">
                        <input type="hidden" name="controller" value="admin">
                        <input type="hidden" name="action" value="products">
                        
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Tìm tên sản phẩm, ID..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-select">
                                <option value="">-- Tất cả danh mục --</option>
                                <?php if(!empty($categoriesList)): ?>
                                    <?php foreach($categoriesList as $c): ?>
                                        <?php $selected = (isset($_GET['category']) && $_GET['category'] == $c['id']) ? 'selected' : ''; ?>
                                        <option value="<?php echo $c['id']; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($c['name']); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="brand_id" class="form-select">
                                <option value="">-- Tất cả thương hiệu --</option>
                                <?php if(!empty($brandsList)): ?>
                                    <?php foreach($brandsList as $b): ?>
                                        <?php $selected = (isset($_GET['brand_id']) && $_GET['brand_id'] == $b['id']) ? 'selected' : ''; ?>
                                        <option value="<?php echo $b['id']; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($b['name']); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 table-custom">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="min-width: 1000px;">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">ID</th>
                                <th class="py-3">Hình ảnh</th>
                                <th class="py-3">Tên sản phẩm</th>
                                <th class="py-3">Danh mục</th>
                                <th class="py-3">Thương hiệu</th>
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
                                    $category = isset($row["category_name"]) && $row["category_name"] ? htmlspecialchars($row["category_name"]) : "Chưa phân loại";
                                    $price = isset($row["price"]) ? number_format($row["price"]) : "0";
                                    $status = isset($row["status"]) ? $row["status"] : 1;
                                    $stock = isset($row['stock']) ? intval($row['stock']) : 0;
                                    
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
                                        <td><span class='badge bg-secondary text-white'><?php echo isset($row['brand_name']) && $row['brand_name'] ? htmlspecialchars($row['brand_name']) : 'Khác'; ?></span></td>
                                        <td class='fw-bold' style='color: var(--brand-dark)'><?php echo $price; ?>đ</td>
                                        <!-- HIỂN THỊ DỮ LIỆU TỒN KHO -->
                                        <td class='fw-bold text-center <?php echo $stock < 5 ? "text-danger" : "text-primary"; ?>'>
                                            <?php echo $stock; ?>
                                            <?php if($stock < 5 && $stock > 0): ?>
                                                <br><small class="text-danger fw-normal" style="font-size:0.75rem;"><i class="fas fa-exclamation-triangle"></i> Sắp hết</small>
                                            <?php elseif($stock == 0): ?>
                                                <br><small class="text-danger fw-normal" style="font-size:0.75rem;">Hết hàng</small>
                                            <?php endif; ?>
                                        </td>
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
</div>

<?php include 'views/layout/footer.php'; ?>