<?php 
$pageTitle_Header = "Glow Cosmetics (MVC) - Vẻ đẹp tỏa sáng"; 
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<!-- Hero Section -->
<section class="hero-section" style="background-color: #fdf2f8; padding: 60px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-white text-danger shadow-sm px-3 py-2 rounded-pill mb-3 fw-bold">🔥 BST Mùa Hè 2024</span>
                <h1 class="display-4 fw-bold mb-4" style="color: #831843;">Đánh Thức Vẻ Đẹp <br> Tự Nhiên Của Bạn</h1>
                <p class="lead text-secondary mb-5">Khám phá hơn 50+ sản phẩm chăm sóc da và trang điểm cao cấp. Trải nghiệm mua sắm mượt mà với mô hình MVC.</p>
                <div class="d-flex gap-3">
                    <a href="#products-section" class="btn btn-lg text-white shadow-sm px-5 rounded-pill fw-bold" style="background-color: var(--brand-dark, #be185d);">Mua ngay</a>
                </div>
            </div>
            <div class="col-lg-6 text-center mt-5 mt-lg-0">
                <img src="https://images.unsplash.com/photo-1618331835717-801e976710b2?q=80&w=1000&auto=format&fit=crop" class="img-fluid rounded-circle shadow-lg" style="max-height: 400px; border: 10px solid white;">
            </div>
        </div>
    </div>
</section>

<!-- Product List Section -->
<section id="products-section" class="container py-5 mt-4">
    <!-- Tiêu đề động được truyền từ Controller -->
    <div class="text-center mb-5">
        <h5 class="fw-bold text-uppercase" style="color: var(--brand-dark, #be185d); letter-spacing: 2px;"><?php echo $subTitle; ?></h5>
        <h2 class="fw-bold display-6 text-dark"><?php echo $pageTitle; ?></h2>
    </div>

    <!-- Thanh tab lọc sản phẩm -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-10 text-center">
            <ul class="nav nav-pills justify-content-center gap-2">
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-4 <?php echo ($currentFilter == 'all') ? 'active' : 'bg-white text-dark border'; ?>" href="index.php?controller=product&action=index&filter=all">Tất cả</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-4 <?php echo ($currentFilter == 'skincare') ? 'active' : 'bg-white text-dark border'; ?>" href="index.php?controller=product&action=index&filter=skincare">Chăm sóc da</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-4 <?php echo ($currentFilter == 'makeup') ? 'active' : 'bg-white text-dark border'; ?>" href="index.php?controller=product&action=index&filter=makeup">Trang điểm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-4 <?php echo ($currentFilter == 'perfume') ? 'active' : 'bg-white text-dark border'; ?>" href="index.php?controller=product&action=index&filter=perfume">Nước hoa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-4 <?php echo ($currentFilter == 'hairbody') ? 'active' : 'bg-white text-dark border'; ?>" href="index.php?controller=product&action=index&filter=hairbody">Cơ thể & Tóc</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Lưới sản phẩm -->
    <div class="row g-4">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $row): 
                $imgUrl = !empty($row['image']) ? $row['image'] : 'https://via.placeholder.com/400x400?text=No+Image';
                if (strpos($imgUrl, 'http') === false) { $imgUrl = $imgUrl; }
                
                // Kiểm tra xem sản phẩm có đang giảm giá hay không
                $isSale = (isset($row['old_price']) && $row['old_price'] > $row['price']);
            ?>
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card border-0 product-card h-100 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                        
                        <!-- Hiển thị % Giảm giá hoặc tem Mới -->
                        <?php if ($isSale): 
                            $discountPercent = round((($row['old_price'] - $row['price']) / $row['old_price']) * 100);
                        ?>
                            <span class="badge bg-danger position-absolute top-0 start-0 m-3 z-3 px-3 py-2 rounded-pill shadow-sm">-<?php echo $discountPercent; ?>%</span>
                        <?php elseif ($currentFilter == 'new'): ?>
                            <span class="badge bg-danger position-absolute top-0 start-0 m-3 z-3 px-3 py-2 rounded-pill shadow-sm">Mới</span>
                        <?php endif; ?>

                        <div class="product-img-container position-relative" style="height: 250px; overflow: hidden;">
                            <img src="<?php echo $imgUrl; ?>" class="w-100 h-100" style="object-fit: cover;" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        </div>
                        
                        <div class="card-body p-3 d-flex flex-column">
                            <div class="text-muted small mb-1"><?php echo htmlspecialchars($row['category']); ?></div>
                            <h6 class="fw-bold mb-2 text-dark" style="height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;" title="<?php echo htmlspecialchars($row['name']); ?>">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </h6>
                            
                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-end">
                                <div>
                                    <!-- Giá cũ bị gạch ngang (nếu có) -->
                                    <?php if ($isSale): ?>
                                        <div class="text-muted small text-decoration-line-through mb-1">
                                            <?php echo number_format($row['old_price']); ?>đ
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Giá bán hiện tại -->
                                    <div class="fw-bold fs-5" style="color: var(--brand-dark, #be185d);">
                                        <?php echo number_format($row['price']); ?>đ
                                    </div>
                                </div>
                                
                                <a href="index.php?controller=cart&action=add&id=<?php echo $row['id']; ?>" class="btn btn-light text-danger rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; transition: all 0.2s;" title="Thêm vào giỏ" onmouseover="this.classList.replace('btn-light','btn-danger'); this.classList.replace('text-danger','text-white')" onmouseout="this.classList.replace('btn-danger','btn-light'); this.classList.replace('text-white','text-danger')">
                                    <i class="fas fa-cart-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có sản phẩm nào trong danh mục này.</h5>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'views/layout/footer.php'; ?>