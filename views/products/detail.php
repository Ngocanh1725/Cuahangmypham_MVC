<?php 
// Tiêu đề trang
$pageTitle_Header = isset($pageTitle) ? $pageTitle : ($product['name'] ?? 'Chi tiết sản phẩm'); 

$extraCSS = "
<style>
    /* ==========================================
       1. BREADCRUMB & CƠ BẢN
       ========================================== */
    .breadcrumb-custom { font-size: 0.9rem; margin-bottom: 30px; }
    .breadcrumb-custom a { color: var(--text-gray, #6b7280); text-decoration: none; transition: color 0.2s; }
    .breadcrumb-custom a:hover { color: var(--brand-primary, #db2777); }
    .breadcrumb-custom .active { color: var(--text-dark, #1f2937); font-weight: 500; }

    /* ==========================================
       2. THƯ VIỆN ẢNH SẢN PHẨM (GALLERY)
       ========================================== */
    .product-gallery { display: flex; flex-direction: column; gap: 15px; }
    .main-image-box {
        background-color: var(--bg-card, #f8fafc);
        border-radius: 20px; padding: 40px; height: 500px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid #f0f0f0;
    }
    .main-image-box img { max-width: 100%; max-height: 100%; object-fit: contain; mix-blend-mode: multiply; transition: opacity 0.3s ease; }
    
    .thumbnail-row { display: flex; gap: 15px; overflow-x: auto; padding-bottom: 5px; }
    .thumb-img {
        width: 80px; height: 80px; border-radius: 12px; background: var(--bg-card, #f8fafc);
        padding: 10px; cursor: pointer; border: 2px solid transparent;
        transition: all 0.3s ease; mix-blend-mode: multiply;
    }
    .thumb-img:hover { border-color: #fbcfe8; }
    .thumb-img.active { border-color: var(--brand-primary, #db2777); background: white; box-shadow: 0 4px 10px rgba(219, 39, 119, 0.1); }

    /* ==========================================
       3. THÔNG TIN SẢN PHẨM (BÊN PHẢI)
       ========================================== */
    .product-category-tag {
        font-size: 0.8rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 1px; color: var(--brand-primary, #db2777); margin-bottom: 10px; display: block;
    }
    .product-title-main {
        font-family: var(--font-heading, 'Playfair Display', serif);
        font-size: 2.2rem; font-weight: 700; color: var(--text-dark, #1f2937);
        line-height: 1.3; margin-bottom: 15px;
    }
    
    .rating-box { font-size: 0.9rem; color: #fbbf24; margin-bottom: 20px; display: flex; align-items: center;}
    .rating-box .reviews-count { color: var(--text-gray, #6b7280); margin-left: 10px; font-size: 0.85rem; }

    .price-box { display: flex; align-items: flex-end; gap: 15px; margin-bottom: 25px; padding-bottom: 25px; border-bottom: 1px solid #f0f0f0;}
    .price-current { font-size: 2.2rem; font-weight: 700; color: var(--brand-primary, #db2777); line-height: 1;}
    .price-old { font-size: 1.2rem; color: #9ca3af; text-decoration: line-through; margin-bottom: 4px;}
    .discount-badge { background: #fef2f2; color: #ef4444; padding: 4px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;}

    .product-short-desc { font-size: 1rem; color: var(--text-gray, #6b7280); line-height: 1.7; margin-bottom: 30px; }

    /* Nút số lượng */
    .quantity-selector { display: flex; align-items: center; background: #f9fafb; border-radius: 50px; width: 140px; padding: 5px; border: 1px solid #e5e7eb;}
    .qty-btn { border: none; background: transparent; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: var(--text-dark, #1f2937); cursor: pointer; border-radius: 50%; transition: background 0.2s;}
    .qty-btn:hover { background: #e5e7eb; }
    .qty-input { width: 50px; text-align: center; border: none; background: transparent; font-weight: 600; font-size: 1.1rem; outline: none; }
    
    /* Nút hành động */
    .btn-action-group { display: flex; gap: 15px; margin-top: 30px; }
    .btn-add-cart-outline {
        flex: 1; background: transparent; color: var(--brand-primary, #db2777);
        border: 2px solid var(--brand-primary, #db2777); border-radius: 50px;
        font-weight: 600; font-size: 1rem; padding: 15px 0; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;
    }
    .btn-add-cart-outline:hover { background: var(--brand-secondary, #fce7f3); }
    
    .btn-buy-now {
        flex: 1; background: var(--brand-primary, #db2777); color: white; border: 2px solid var(--brand-primary, #db2777);
        border-radius: 50px; font-weight: 600; font-size: 1rem; padding: 15px 0; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;
        box-shadow: 0 8px 20px rgba(219, 39, 119, 0.2);
    }
    .btn-buy-now:hover { background: var(--brand-dark, #be185d); border-color: var(--brand-dark, #be185d); transform: translateY(-2px); color: white;}

    .trust-badges { display: flex; gap: 20px; margin-top: 30px; padding-top: 25px; border-top: 1px solid #f0f0f0; }
    .trust-item { display: flex; align-items: center; gap: 10px; font-size: 0.85rem; color: var(--text-gray, #6b7280); }
    .trust-item i { font-size: 1.5rem; color: var(--brand-primary, #db2777); }

    /* ==========================================
       4. TABS THÔNG TIN SẢN PHẨM
       ========================================== */
    .product-tabs-section { margin-top: 80px; }
    .nav-tabs-custom { border-bottom: 2px solid #f0f0f0; justify-content: center; gap: 40px; }
    .nav-tabs-custom .nav-link {
        border: none; color: var(--text-gray, #6b7280); font-weight: 600; font-size: 1.1rem;
        text-transform: uppercase; letter-spacing: 1px; padding: 15px 0; background: transparent; position: relative;
    }
    .nav-tabs-custom .nav-link::after {
        content: ''; position: absolute; bottom: -2px; left: 0; width: 100%; height: 2px;
        background: var(--brand-primary, #db2777); transform: scaleX(0); transition: transform 0.3s ease;
    }
    .nav-tabs-custom .nav-link.active { color: var(--brand-primary, #db2777); }
    .nav-tabs-custom .nav-link.active::after { transform: scaleX(1); }
    
    .tab-content-custom { padding: 40px 0; font-size: 1.05rem; line-height: 1.8; color: var(--text-gray, #6b7280); }
    .tab-content-custom h5 { color: var(--text-dark, #1f2937); font-family: var(--font-heading, 'Playfair Display', serif); font-size: 1.5rem; margin-bottom: 20px;}
    .tab-content-custom ul { padding-left: 20px; }
    .tab-content-custom ul li { margin-bottom: 10px; }
    
    /* Reviews */
    .review-item { padding-bottom: 20px; border-bottom: 1px solid #f0f0f0; margin-bottom: 20px; }
    .review-item:last-child { border-bottom: none; }
    .reviewer-name { font-weight: 600; color: var(--text-dark, #1f2937); margin-right: 10px;}
    .review-date { font-size: 0.85rem; color: #9ca3af; }

    /* CSS cho lưới sản phẩm liên quan tái sử dụng */
    .product-card { width: 100%; position: relative; border: none; background: transparent;}
    .product-img-container { height: 250px; background-color: var(--bg-card, #f8fafc); border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid #f0f0f0; }
    .product-img-container img { max-height: 85%; max-width: 85%; object-fit: contain; transition: transform 0.6s ease; mix-blend-mode: multiply;}
    .product-card:hover .product-img-container img { transform: scale(1.08); }
    .product-brand-name { font-size: 0.75rem; color: var(--text-gray, #6b7280); letter-spacing: 1px; font-weight: 600; }
    .product-title-clamp { font-family: var(--font-heading, 'Playfair Display', serif); font-size: 1.1rem; font-weight: 600; color: var(--text-dark, #1f2937); line-height: 1.4em; height: 2.8em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin-bottom: 5px; transition: color 0.3s;}
    .product-title-clamp:hover a { color: var(--brand-primary, #db2777) !important; }
</style>
";
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 

// Xử lý dữ liệu hiển thị (Tránh lỗi nếu thiếu dữ liệu)
$imgUrl = (!empty($product['image']) && $product['image'] !== 'https://via.placeholder.com/300x300?text=No+Image') 
          ? $product['image'] 
          : 'https://via.placeholder.com/600x600?text=Beauty+Product';
          
$isSale = (isset($product['old_price']) && $product['old_price'] > $product['price']);
$category = isset($product['category_name']) && $product['category_name'] ? $product['category_name'] : 'Sản phẩm làm đẹp';
?>

<div class="container py-5">
    
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <a href="index.php">Trang chủ</a> <span class="mx-2">/</span> 
        <a href="index.php?controller=product&action=index">Cửa hàng</a> <span class="mx-2">/</span> 
        <a href="index.php?controller=product&action=index&category[]=<?php echo urlencode($category); ?>"><?php echo htmlspecialchars($category); ?></a> <span class="mx-2">/</span> 
        <span class="active"><?php echo htmlspecialchars($product['name']); ?></span>
    </div>

    <!-- PHẦN 1: HÌNH ẢNH VÀ THÔNG TIN CƠ BẢN -->
    <div class="row g-5">
        <!-- Cột Trái: Gallery Hình ảnh -->
        <div class="col-lg-6">
            <div class="product-gallery">
                <div class="main-image-box position-relative">
                    <?php if ($isSale): 
                        $discountPercent = round((($product['old_price'] - $product['price']) / $product['old_price']) * 100);
                    ?>
                        <span class="position-absolute top-0 start-0 m-4 z-3 px-3 py-1 bg-white border border-danger text-danger rounded-pill fw-bold">-<?php echo $discountPercent; ?>%</span>
                    <?php endif; ?>
                    <img id="mainProductImage" src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                
                <div class="thumbnail-row">
                    <img src="<?php echo htmlspecialchars($imgUrl); ?>" class="thumb-img active" onclick="changeImage(this, '<?php echo htmlspecialchars($imgUrl); ?>')">
                    <!-- Ảnh mockup chức năng click -->
                    <img src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?q=80&w=300&auto=format&fit=crop" class="thumb-img" onclick="changeImage(this, 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?q=80&w=800&auto=format&fit=crop')">
                </div>
            </div>
        </div>

        <!-- Cột Phải: Chi tiết thông tin -->
        <div class="col-lg-6">
            <span class="product-category-tag"><?php echo htmlspecialchars($category); ?></span>
            <h1 class="product-title-main"><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <div class="rating-box">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                <span class="reviews-count">(128 đánh giá) | Đã bán 2.4k</span>
            </div>

            <div class="price-box">
                <span class="price-current"><?php echo number_format($product['price']); ?>đ</span>
                <?php if ($isSale): ?>
                    <span class="price-old"><?php echo number_format($product['old_price']); ?>đ</span>
                    <span class="discount-badge">Giảm <?php echo number_format($product['old_price'] - $product['price']); ?>đ</span>
                <?php endif; ?>
            </div>

            <p class="product-short-desc">
                <?php 
                    if (!empty($product['description'])) {
                        echo nl2br(htmlspecialchars($product['description']));
                    } else {
                        echo "Công thức dịu nhẹ, thẩm thấu nhanh giúp làm sáng da và cung cấp độ ẩm sâu. Thành phần chiết xuất từ thiên nhiên, an toàn cho cả làn da nhạy cảm nhất. Trải nghiệm sự thay đổi khác biệt chỉ sau 14 ngày sử dụng.";
                    }
                ?>
            </p>

            <form action="index.php?controller=cart&action=add&id=<?php echo $product['id']; ?>" method="POST" id="addToCartForm">
                <?php if (isset($product['stock']) && $product['stock'] > 0): ?>
                    <h6 class="fw-bold mb-3 text-dark">Số lượng</h6>
                    <div class="quantity-selector">
                        <button type="button" class="qty-btn" onclick="decreaseQty()"><i class="fas fa-minus"></i></button>
                        <input type="number" name="qty" id="qtyInput" class="qty-input" value="1" min="1" max="<?php echo $product['stock']; ?>">
                        <button type="button" class="qty-btn" onclick="increaseQty()"><i class="fas fa-plus"></i></button>
                    </div>
                    
                    <div class="mt-2 mb-4 text-muted small">
                        Kho còn: <strong class="text-success"><?php echo $product['stock']; ?> sản phẩm</strong>
                    </div>

                    <div class="btn-action-group">
                        <button type="submit" class="btn btn-add-cart-outline">
                            <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ
                        </button>
                        <button type="button" class="btn btn-buy-now" onclick="buyNow()">
                            Mua Ngay
                        </button>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger mt-3 d-inline-block fw-bold py-2 px-4 rounded-pill">
                        <i class="fas fa-exclamation-circle me-2"></i> Sản phẩm hiện đang hết hàng
                    </div>
                <?php endif; ?>
            </form>

            <div class="trust-badges">
                <div class="trust-item"><i class="fas fa-shield-alt"></i> <span>100%<br>Chính Hãng</span></div>
                <div class="trust-item"><i class="fas fa-box-open"></i> <span>Đổi trả<br>7 ngày</span></div>
                <div class="trust-item"><i class="fas fa-shipping-fast"></i> <span>Miễn phí<br>Giao hàng</span></div>
            </div>
        </div>
    </div>

    <!-- PHẦN 2: TABS THÔNG TIN CHI TIẾT -->
    <div class="product-tabs-section">
        <ul class="nav nav-tabs nav-tabs-custom" id="productTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button" role="tab">Mô tả chi tiết</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Thông tin & Thành phần</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Đánh giá (128)</button>
            </li>
        </ul>
        
        <div class="tab-content tab-content-custom" id="productTabContent">
            <!-- Tab Mô tả -->
            <div class="tab-pane fade show active" id="desc" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <h5>Câu chuyện sản phẩm</h5>
                        <p>Mang đến trải nghiệm chăm sóc da hoàn toàn mới, <strong><?php echo htmlspecialchars($product['name']); ?></strong> được nghiên cứu chuyên sâu để giải quyết các vấn đề về da liễu. Với kết cấu mỏng nhẹ, sản phẩm tan ngay vào da mà không gây bết dính.</p>
                        <p>Được yêu thích bởi hàng ngàn tín đồ làm đẹp, đây chính là "chân ái" giúp bạn khơi dậy vẻ đẹp tự nhiên (Natural Glow) rạng rỡ từ bên trong.</p>
                        <br>
                        <h5>Công dụng nổi bật</h5>
                        <ul>
                            <li>Cấp ẩm sâu, duy trì làn da căng mọng suốt 24 giờ.</li>
                            <li>Hỗ trợ làm sáng da, đều màu và làm mờ các vết thâm sạm.</li>
                            <li>Tạo lớp màng bảo vệ da khỏi các tác nhân gây hại từ môi trường.</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Tab Thành phần -->
            <div class="tab-pane fade" id="info" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <h5>Thành phần chính (Key Ingredients)</h5>
                        <p>Chúng tôi cam kết sử dụng nguồn nguyên liệu an toàn, không chứa Paraben, không Sulfate và không thử nghiệm trên động vật (Cruelty-Free).</p>
                        <ul>
                            <li><strong>Hyaluronic Acid:</strong> Phân tử "ngậm nước" giúp da luôn ngậm nước và đàn hồi.</li>
                            <li><strong>Niacinamide (Vitamin B3):</strong> Thu nhỏ lỗ chân lông, kiềm dầu và làm sáng da an toàn.</li>
                        </ul>
                        <br>
                        <h5>Hướng dẫn sử dụng</h5>
                        <p>1. Làm sạch da mặt cơ bản bằng tẩy trang và sữa rửa mặt.<br>
                           2. Lấy một lượng sản phẩm vừa đủ ra tay.<br>
                           3. Thoa đều lên mặt và cổ, massage nhẹ nhàng để thẩm thấu.<br>
                           4. Sử dụng đều đặn 2 lần/ngày (Sáng và Tối).</p>
                    </div>
                </div>
            </div>
            
            <!-- Tab Đánh giá -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="d-flex align-items-center mb-5 bg-light p-4 rounded-4 border">
                            <div class="text-center me-5">
                                <h1 class="display-4 fw-bold" style="color: var(--brand-primary, #db2777);">4.8</h1>
                                <div class="text-warning mb-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
                                <span class="text-muted small">Dựa trên 128 đánh giá</span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1"><span class="small me-2">5 <i class="fas fa-star text-warning"></i></span> <div class="progress flex-grow-1" style="height: 8px;"><div class="progress-bar bg-warning" style="width: 85%"></div></div></div>
                                <div class="d-flex align-items-center mb-1"><span class="small me-2">4 <i class="fas fa-star text-warning"></i></span> <div class="progress flex-grow-1" style="height: 8px;"><div class="progress-bar bg-warning" style="width: 10%"></div></div></div>
                                <div class="d-flex align-items-center mb-1"><span class="small me-2">3 <i class="fas fa-star text-warning"></i></span> <div class="progress flex-grow-1" style="height: 8px;"><div class="progress-bar bg-warning" style="width: 3%"></div></div></div>
                                <div class="d-flex align-items-center mb-1"><span class="small me-2">2 <i class="fas fa-star text-warning"></i></span> <div class="progress flex-grow-1" style="height: 8px;"><div class="progress-bar bg-warning" style="width: 1%"></div></div></div>
                                <div class="d-flex align-items-center mb-1"><span class="small me-2">1 <i class="fas fa-star text-warning"></i></span> <div class="progress flex-grow-1" style="height: 8px;"><div class="progress-bar bg-warning" style="width: 1%"></div></div></div>
                            </div>
                        </div>

                        <div class="review-item">
                            <div class="d-flex justify-content-between mb-2">
                                <div><span class="reviewer-name">Ngọc Trinh</span> <i class="fas fa-check-circle text-success small"></i> <span class="text-success small">Đã mua hàng</span></div>
                                <span class="review-date">12/05/2024</span>
                            </div>
                            <div class="text-warning small mb-2"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                            <p class="mb-0">Sản phẩm dùng rất thích, chất kem mịn thấm nhanh không bị nhờn rít. Đóng gói rất cẩn thận và đẹp mắt. Sẽ ủng hộ shop lâu dài!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PHẦN 3: SẢN PHẨM LIÊN QUAN TỪ DATABASE BƯỚC 6 -->
    <div class="mt-5 pt-5 border-top">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <h3 class="fw-bold mb-0" style="font-family: var(--font-heading); color: var(--text-dark);">Có thể bạn cũng thích</h3>
            <a href="index.php?controller=product&action=index&category[]=<?php echo urlencode($category); ?>" class="text-dark fw-medium text-decoration-none border-bottom border-dark pb-1 d-none d-md-block">Xem thêm <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php if(!empty($relatedProducts)): ?>
                <?php foreach($relatedProducts as $rel): 
                    $relImg = !empty($rel['image']) ? $rel['image'] : 'https://via.placeholder.com/300x300';
                ?>
                <div class="col-6 col-md-3">
                    <div class="product-card">
                        <div class="product-img-container" style="height: 250px;">
                            <a href="index.php?controller=product&action=detail&id=<?php echo $rel['id']; ?>">
                                <img src="<?php echo htmlspecialchars($relImg); ?>" alt="<?php echo htmlspecialchars($rel['name']); ?>">
                            </a>
                        </div>
                        <div class="text-center mt-3 px-1">
                            <p class="product-brand-name text-uppercase text-muted small mb-1"><?php echo isset($rel['category_name']) ? htmlspecialchars($rel['category_name']) : 'Sản phẩm'; ?></p>
                            <h3 class="product-title-clamp" style="font-size: 1.1rem;">
                                <a href="index.php?controller=product&action=detail&id=<?php echo $rel['id']; ?>" class="text-decoration-none" style="color: inherit;">
                                    <?php echo htmlspecialchars($rel['name']); ?>
                                </a>
                            </h3>
                            <div class="product-price mt-2" style="font-size: 1rem; color: var(--brand-primary, #db2777); font-weight: 600;">
                                <?php echo number_format($rel['price']); ?>đ
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-4 text-muted border bg-light rounded-4">
                    <i class="fas fa-box-open fa-2x mb-2 text-secondary"></i>
                    <p class="mb-0">Chưa có sản phẩm nào cùng loại.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
// JavaScript xử lý Gallery và Nút Mua hàng
$extraJS = "
<script>
    // 1. Đổi ảnh khi click vào thumbnail
    function changeImage(element, newSrc) {
        const mainImg = document.getElementById('mainProductImage');
        mainImg.style.opacity = 0.5;
        
        setTimeout(() => {
            mainImg.src = newSrc;
            mainImg.style.opacity = 1;
        }, 150);

        const thumbs = document.querySelectorAll('.thumb-img');
        thumbs.forEach(t => t.classList.remove('active'));
        element.classList.add('active');
    }

    // 2. Nút Tăng/Giảm số lượng
    const qtyInput = document.getElementById('qtyInput');
    const maxStock = parseInt(qtyInput.getAttribute('max'));

    function decreaseQty() {
        let currentVal = parseInt(qtyInput.value);
        if (currentVal > 1) {
            qtyInput.value = currentVal - 1;
        }
    }

    function increaseQty() {
        let currentVal = parseInt(qtyInput.value);
        if (currentVal < maxStock) {
            qtyInput.value = currentVal + 1;
        } else {
            alert('Bạn đã chọn tối đa số lượng hàng có trong kho!');
        }
    }

    // 3. Xử lý nút Mua Ngay
    function buyNow() {
        const form = document.getElementById('addToCartForm');
        form.submit();
    }
</script>
";
include 'views/layout/footer.php'; 
?>