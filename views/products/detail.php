<?php 
// Đã khai báo $pageTitle_Header từ Controller
$extraCSS = "
<style>
    .product-detail-img { width: 100%; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f0f0f0; }
    .breadcrumb-custom { font-size: 0.9rem; margin-bottom: 20px; }
    .breadcrumb-custom a { color: #6b7280; text-decoration: none; transition: color 0.2s; }
    .breadcrumb-custom a:hover { color: var(--brand-dark, #be185d); }
    .product-title-main { font-size: 1.8rem; font-weight: 800; color: #1f2937; line-height: 1.3; }
    .price-main { font-size: 2rem; font-weight: 900; color: var(--brand-dark, #be185d); }
    .price-old { font-size: 1.2rem; color: #9ca3af; text-decoration: line-through; margin-left: 10px; }
    
    .delivery-info-box { border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-top: 25px; }
    .delivery-info-box h6 { font-weight: 700; margin-bottom: 15px; }
    
    .btn-buy-now { background: linear-gradient(90deg, #f59e0b, #db2777); color: white; border: none; font-weight: bold; transition: all 0.3s; }
    .btn-buy-now:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(219, 39, 119, 0.4); color: white;}
    .btn-add-cart-outline { border: 2px solid var(--brand-dark, #be185d); color: var(--brand-dark, #be185d); font-weight: bold; transition: all 0.3s; }
    .btn-add-cart-outline:hover { background-color: var(--brand-dark, #be185d); color: white; }
    
    .policy-list { list-style: none; padding: 0; margin-top: 20px; font-size: 0.9rem; color: #4b5563;}
    .policy-list li { margin-bottom: 10px; display: flex; align-items: center;}
    .policy-list i { color: var(--brand-dark, #be185d); margin-right: 10px; font-size: 1.1rem;}
</style>
";
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 

// Xử lý dữ liệu hiển thị
$imgUrl = !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/600x600?text=No+Image';
$isSale = (isset($product['old_price']) && $product['old_price'] > $product['price']);
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <a href="index.php">Trang chủ</a> 
        <span class="mx-2 text-muted">/</span> 
        <a href="index.php?controller=product&action=index">Sản phẩm</a> 
        <span class="mx-2 text-muted">/</span> 
        <span class="text-dark fw-medium"><?php echo htmlspecialchars($product['category']); ?></span>
    </div>

    <div class="row g-5">
        <!-- Cột Ảnh Sản Phẩm -->
        <div class="col-md-5">
            <div class="position-relative">
                <?php if ($isSale): 
                    $discountPercent = round((($product['old_price'] - $product['price']) / $product['old_price']) * 100);
                ?>
                    <span class="badge bg-danger position-absolute top-0 start-0 m-3 fs-6 px-3 py-2 rounded-pill shadow z-3">-<?php echo $discountPercent; ?>%</span>
                <?php endif; ?>
                <img src="<?php echo $imgUrl; ?>" class="product-detail-img img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            
            <!-- Ảnh nhỏ bên dưới (Mockup) -->
            <div class="d-flex gap-3 mt-4 overflow-auto pb-2">
                <img src="<?php echo $imgUrl; ?>" class="border border-primary rounded-3" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                <img src="<?php echo $imgUrl; ?>" class="border rounded-3 opacity-50" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;" onmouseover="this.classList.remove('opacity-50')" onmouseout="this.classList.add('opacity-50')">
            </div>
        </div>

        <!-- Cột Thông tin Sản Phẩm -->
        <div class="col-md-7">
            <div class="mb-2">
                <span class="badge bg-dark bg-opacity-10 text-dark fw-bold text-uppercase px-3 py-2 rounded-pill"><?php echo htmlspecialchars($product['category']); ?></span>
            </div>
            <h1 class="product-title-main mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <div class="d-flex align-items-center mb-4 small text-muted">
                <div class="text-warning me-2"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
                <span class="me-3">(152 đánh giá)</span> | 
                <span class="mx-3">Đã bán: 1.2k</span> |
                <span class="ms-3 text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Còn hàng</span>
            </div>

            <div class="d-flex align-items-end mb-4 bg-light p-3 rounded-4">
                <span class="price-main"><?php echo number_format($product['price']); ?>đ</span>
                <?php if ($isSale): ?>
                    <span class="price-old pb-1"><?php echo number_format($product['old_price']); ?>đ</span>
                <?php endif; ?>
            </div>

            <p class="text-secondary mb-4" style="line-height: 1.6;">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </p>

            <!-- Box Giao hàng & Cửa hàng -->
            <div class="delivery-info-box mb-4">
                <h6>Hình thức mua hàng</h6>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="delivery" id="del1" checked>
                    <label class="form-check-label fw-medium text-dark" for="del1">
                        <i class="fas fa-truck text-muted me-2"></i> Giao hàng tận nơi
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="delivery" id="del2">
                    <label class="form-check-label fw-medium text-dark" for="del2">
                        <i class="fas fa-store text-muted me-2"></i> Click & Collect (Mua và lấy hàng tại cửa hàng)
                    </label>
                </div>
                <hr class="text-muted">
                <a href="index.php?controller=page&action=stores" class="text-danger text-decoration-none fw-bold small"><i class="fas fa-map-marker-alt me-1"></i> Xem danh sách cửa hàng có sẵn sản phẩm</a>
            </div>

            <!-- Các Nút Hành Động -->
            <div class="d-flex gap-3">
                <a href="index.php?controller=cart&action=add&id=<?php echo $product['id']; ?>" class="btn btn-add-cart-outline flex-grow-1 rounded-pill py-3 fs-5">
                    <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ hàng
                </a>
                <a href="index.php?controller=cart&action=add&id=<?php echo $product['id']; ?>" class="btn btn-buy-now flex-grow-1 rounded-pill py-3 fs-5" onclick="setTimeout(()=>{window.location.href='index.php?controller=cart&action=index'}, 500);">
                    Mua Ngay
                </a>
            </div>

            <!-- Chính sách -->
            <ul class="policy-list row">
                <div class="col-6">
                    <li><i class="fas fa-shield-alt"></i> Cam kết 100% chính hãng</li>
                    <li><i class="fas fa-undo"></i> Đổi trả miễn phí 7 ngày</li>
                </div>
                <div class="col-6">
                    <li><i class="fas fa-box-open"></i> Đồng kiểm khi nhận hàng</li>
                    <li><i class="fas fa-headset"></i> Hỗ trợ nhiệt tình 24/7</li>
                </div>
            </ul>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>