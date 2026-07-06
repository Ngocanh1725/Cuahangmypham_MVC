<?php 
$pageTitle_Header = isset($pageTitle) ? $pageTitle : "Glow Cosmetics - Vẻ đẹp tỏa sáng"; 

// Biến kiểm tra: Mở trạng thái Trang Chủ nếu không có query lọc
$isHomepage = empty($_GET['keyword']) && empty($_GET['category']) && empty($_GET['brand']) && empty($_GET['price']) && (empty($_GET['filter']) || $_GET['filter'] == 'all');

$extraCSS = "
<!-- Nhúng thư viện Swiper CSS cho Banner -->
<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css\" />

<style>
    .hero-section-wrapper { margin-bottom: 50px; }
    .hero-swiper { width: 100%; padding-bottom: 30px; }
    .hero-swiper .swiper-slide { 
        height: 70vh; min-height: 500px; max-height: 650px;
        background-color: var(--brand-secondary, #fce7f3); 
        position: relative; border-radius: 0 0 40px 40px; overflow: hidden;
    }
    .hero-swiper img { width: 100%; height: 100%; object-fit: cover; object-position: top center; }
    .banner-overlay-text { 
        position: absolute; top: 50%; left: 8%; transform: translateY(-50%); 
        max-width: 500px; background: rgba(255, 255, 255, 0.9); 
        padding: 50px; border-radius: 12px; backdrop-filter: blur(8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
    }
    .banner-brand-title { font-family: var(--font-heading, 'Playfair Display', serif); font-size: 3.5rem; color: var(--brand-primary, #db2777); margin-bottom: 20px; font-weight: 700; line-height: 1.1; }
    .banner-desc { font-size: 1.05rem; color: var(--text-gray, #6b7280); line-height: 1.6; margin-bottom: 30px; }
    .hero-swiper .swiper-button-next, .hero-swiper .swiper-button-prev { color: var(--brand-primary, #db2777); background: white; width: 50px; height: 50px; border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .hero-swiper .swiper-pagination-bullet-active { background: var(--brand-primary, #db2777); }

    .shop-categories { margin-bottom: 60px; }
    .section-title-elegant { font-family: var(--font-heading, 'Playfair Display', serif); color: var(--brand-primary, #db2777); font-size: 2.5rem; font-weight: 700; margin-bottom: 40px; }
    .category-card { display: block; text-align: center; text-decoration: none !important; transition: all 0.3s ease; }
    .cat-img-box { background-color: var(--bg-card, #f8fafc); border-radius: 16px; padding: 30px; height: 220px; display: flex; align-items: center; justify-content: center; transition: all 0.4s ease; margin-bottom: 15px; }
    .cat-img-box img { max-width: 85%; max-height: 85%; object-fit: contain; mix-blend-mode: multiply; transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
    .category-card:hover .cat-img-box { background-color: white; box-shadow: 0 15px 35px rgba(219, 39, 119, 0.08); transform: translateY(-8px); }
    .category-card:hover .cat-img-box img { transform: scale(1.15); }
    .cat-name { color: var(--text-dark, #1f2937); font-size: 1.15rem; transition: color 0.3s; font-family: var(--font-heading, 'Playfair Display', serif); font-weight: 600;}
    .category-card:hover .cat-name { color: var(--brand-primary, #db2777); }

    /* Best Sellers */
    .bestsellers-section { margin-bottom: 60px; }
    .bestseller-card { background: transparent; transition: transform 0.3s ease; }
    .bestseller-img-box { position: relative; border-radius: 16px; padding: 20px; height: 260px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; overflow: hidden; transition: all 0.3s ease; }
    .bestseller-img-box img { max-width: 80%; max-height: 80%; object-fit: contain; transition: transform 0.5s ease; mix-blend-mode: multiply; }
    .bestseller-card:hover .bestseller-img-box img { transform: scale(1.1); }
    .wishlist-btn { position: absolute; top: 15px; right: 15px; background: white; border: none; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-gray, #6b7280); box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: all 0.3s ease; z-index: 2; }
    .wishlist-btn:hover { color: var(--brand-primary, #db2777); transform: scale(1.1); }
    .cart-btn-circle { position: absolute; bottom: -50px; right: 15px; background: var(--brand-dark, #be185d); color: white; border: none; width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(190, 24, 93, 0.3); transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 2; opacity: 0; font-size: 1.1rem; }
    .bestseller-card:hover .cart-btn-circle { bottom: 15px; opacity: 1; }
    .cart-btn-circle:hover { background: var(--brand-primary, #db2777); transform: scale(1.1); color: white;}
    .product-brand-name { font-size: 0.75rem; color: var(--text-gray, #6b7280); letter-spacing: 1px; margin-bottom: 5px; font-weight: 600; }
    .product-title-link { font-family: var(--font-heading, 'Playfair Display', serif); font-size: 1.1rem; font-weight: 600; color: var(--text-dark, #1f2937); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.3s; }
    .product-title-link:hover { color: var(--brand-primary, #db2777); }

    /* Lọc & Danh sách SP */
    .filter-sidebar { background: white; padding: 25px; border-radius: 12px; border: 1px solid #f0f0f0; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
    .filter-group { border-bottom: 1px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; }
    .filter-group:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .filter-title { font-family: var(--font-heading, 'Playfair Display', serif); font-size: 1.15rem; color: var(--brand-primary, #db2777); cursor: pointer; display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; font-weight: 600;}
    .filter-title::after { content: '\\f106'; font-family: 'Font Awesome 5 Free'; font-weight: 900; transition: transform 0.3s ease; font-size: 0.9rem; color: #9ca3af; }
    .filter-title.collapsed::after { transform: rotate(180deg); }
    .filter-search-box { position: relative; margin-bottom: 15px; }
    .filter-search-box input { width: 100%; padding: 10px 10px 10px 35px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.85rem; outline: none; background: #f9fafb;}
    .filter-search-box input:focus { border-color: var(--brand-primary, #db2777); background: white;}
    .filter-search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.85rem; }
    .filter-list { list-style: none; padding: 0; margin: 0; }
    .filter-item { margin-bottom: 12px; }
    .filter-item label { display: flex; align-items: center; cursor: pointer; font-size: 0.9rem; color: #4b5563; transition: color 0.2s; }
    .filter-item label:hover { color: var(--brand-primary, #db2777); }
    .filter-item input[type=\"checkbox\"] { margin-right: 10px; width: 16px; height: 16px; accent-color: var(--brand-primary, #db2777); cursor: pointer; border-radius: 4px;}
    .filter-item.hidden-item { display: none; }
    .btn-view-more { color: #6b7280; background: none; border: none; padding: 0; font-size: 0.85rem; font-weight: 500; text-decoration: underline; cursor: pointer; margin-top: 5px;}
    .btn-view-more:hover { color: var(--brand-primary, #db2777); }

    .product-card { width: 100%; position: relative; }
    .product-img-container { height: 280px; background-color: var(--bg-card, #f8fafc); border-radius: 12px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; border: 1px solid #f0f0f0; }
    .product-img-container img { max-height: 85%; max-width: 85%; object-fit: contain; transition: transform 0.6s ease; }
    .product-card:hover .product-img-container img { transform: scale(1.08); }
    .quick-view-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: all 0.3s ease; backdrop-filter: blur(2px); }
    .product-card:hover .quick-view-overlay { opacity: 1; }
    .btn-quick-view { background: white !important; color: var(--brand-primary, #db2777) !important; border: 1px solid var(--brand-primary, #db2777) !important; padding: 10px 25px; border-radius: 30px; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; transform: translateY(20px); transition: all 0.3s; }
    .product-card:hover .btn-quick-view { transform: translateY(0); }
    .btn-quick-view:hover { background: var(--brand-primary, #db2777) !important; color: white !important; }
    .product-title-clamp { font-family: var(--font-heading, 'Playfair Display', serif); font-size: 1.15rem; font-weight: 600; color: var(--brand-primary, #db2777); line-height: 1.4em; height: 2.8em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin-bottom: 5px; }

    @media (max-width: 768px) {
        .banner-overlay-text { position: relative; top: auto; left: auto; transform: none; margin: 40px 20px; text-align: center; }
        .hero-swiper .swiper-slide { height: auto; display: flex; flex-direction: column; }
        .product-img-container { height: 200px; }
        .product-title-clamp { font-size: 1rem; }
    }
</style>
";
include 'views/layout/header.php'; 
include 'views/layout/navbar.php'; 
?>

<?php if($isHomepage): ?>
<!-- ========================================================== -->
<!-- 1. HERO SECTION (TRANG CHỦ) -->
<!-- ========================================================== -->
<section class="hero-section-wrapper">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            <?php if (!empty($banners)): ?>
                <?php foreach($banners as $banner): ?>
                    <div class="swiper-slide">
                        <img src="<?php echo htmlspecialchars($banner['image']); ?>" alt="<?php echo htmlspecialchars($banner['title']); ?>" onerror="this.src='https://images.unsplash.com/photo-1596462502278-27bfdc403348?q=80&w=1600&auto=format&fit=crop'">
                        
                        <?php if (!empty($banner['title']) || !empty($banner['brand_name'])): ?>
                            <div class="banner-overlay-text">
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill mb-3 fw-bold"><i class="fas fa-star me-1 text-warning"></i> Bộ Sưu Tập Mới</span>
                                <h2 class="banner-brand-title"><?php echo htmlspecialchars($banner['title']); ?></h2>
                                <p class="banner-desc">
                                    <?php echo !empty($banner['brand_name']) ? "Sản phẩm độc quyền từ thương hiệu <strong>" . htmlspecialchars($banner['brand_name']) . "</strong>." : "Khám phá ngay bí quyết chăm sóc da rạng rỡ tự nhiên."; ?>
                                    <?php echo !empty($banner['ambassador']) ? "<br>Cùng đại sứ: <i>" . htmlspecialchars($banner['ambassador']) . "</i>" : ""; ?>
                                </p>
                                <a href="#products-section" class="btn text-white px-4 py-3 rounded-pill fw-bold" style="background-color: var(--brand-primary, #db2777);">
                                    Khám Phá Ngay <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="swiper-slide">
                    <img src="https://images.unsplash.com/photo-1618331835717-801e976710b2?q=80&w=1600&auto=format&fit=crop" alt="Hero">
                    <div class="banner-overlay-text">
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill mb-3 fw-bold">Skincare 2024</span>
                        <h2 class="banner-brand-title">Reveal Your<br>Natural Glow</h2>
                        <p class="banner-desc">Khám phá dòng sản phẩm chăm sóc da giúp tôn lên vẻ đẹp tự nhiên của bạn. Dịu nhẹ, hiệu quả và được tạo ra dành riêng cho bạn.</p>
                        <a href="#products-section" class="btn text-white px-4 py-3 rounded-pill fw-bold" style="background-color: var(--brand-primary, #db2777);">Mua Ngay <i class="fas fa-shopping-bag ms-2"></i></a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="swiper-button-next d-none d-md-flex"></div>
        <div class="swiper-button-prev d-none d-md-flex"></div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- ========================================================== -->
<!-- 2. SHOP BY CATEGORIES SECTION (TRANG CHỦ) -->
<!-- ========================================================== -->
<section class="shop-categories container mt-5 pt-4">
    <div class="text-center mb-5">
        <h2 class="section-title-elegant">Danh mục nổi bật</h2>
        <p class="text-muted">Tìm kiếm nhanh các sản phẩm bạn cần theo phân loại</p>
    </div>
    
    <div class="row justify-content-center g-4">
        <div class="col-6 col-md-4 col-lg-2">
            <a href="index.php?controller=product&action=index&category[]=Chăm sóc" class="category-card">
                <div class="cat-img-box" style="background-color: #fdf2f8;">
                    <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=300&auto=format&fit=crop" alt="Skincare">
                </div>
                <h6 class="cat-name mb-1">Chăm sóc da</h6>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="index.php?controller=product&action=index&category[]=Trang điểm" class="category-card">
                <div class="cat-img-box" style="background-color: #fff1f2;">
                    <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?q=80&w=300&auto=format&fit=crop" alt="Makeup">
                </div>
                <h6 class="cat-name mb-1">Trang điểm</h6>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="index.php?controller=product&action=index&category[]=Nước hoa" class="category-card">
                <div class="cat-img-box" style="background-color: #f0fdf4;">
                    <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?q=80&w=300&auto=format&fit=crop" alt="Perfume">
                </div>
                <h6 class="cat-name mb-1">Nước hoa</h6>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="index.php?controller=product&action=index&category[]=Cơ thể" class="category-card">
                <div class="cat-img-box" style="background-color: #eff6ff;">
                    <img src="https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?q=80&w=300&auto=format&fit=crop" alt="Body">
                </div>
                <h6 class="cat-name mb-1">Body & Tóc</h6>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="index.php?controller=product&action=index&category[]=Son" class="category-card">
                <div class="cat-img-box" style="background-color: #fef2f2;">
                    <img src="https://images.unsplash.com/photo-1586495777744-4413f21062fa?q=80&w=300&auto=format&fit=crop" alt="Lipstick">
                </div>
                <h6 class="cat-name mb-1">Son môi</h6>
            </a>
        </div>
    </div>
</section>

<!-- ========================================================== -->
<!-- 3. OUR BESTSELLERS SECTION (TRANG CHỦ - DỮ LIỆU ĐỘNG BƯỚC 6) -->
<!-- ========================================================== -->
<section class="bestsellers-section container mt-5 pt-3 mb-5 pb-4 border-bottom">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="section-title-elegant m-0">Our Bestsellers</h2>
            <p class="text-muted mt-2 mb-0">Sản phẩm được yêu thích nhất bởi khách hàng Glow.</p>
        </div>
        <a href="index.php?controller=product&action=index" class="text-dark fw-medium text-decoration-none border-bottom border-dark pb-1 d-none d-md-block">View All <i class="fas fa-angle-right ms-1"></i></a>
    </div>

    <div class="row g-4">
        <?php if (!empty($bestSellers)): ?>
            <?php foreach ($bestSellers as $item): 
                $imgBs = !empty($item['image']) ? $item['image'] : 'https://via.placeholder.com/400x400';
                $isBsSale = (isset($item['old_price']) && $item['old_price'] > $item['price']);
                
                // Mảng màu pastel ngẫu nhiên cho nền thẻ
                $pastelColors = ['#fdf2f8', '#f0fdf4', '#fffbeb', '#eff6ff', '#fef2f2', '#f3e8ff'];
                $randomColor = $pastelColors[array_rand($pastelColors)];
            ?>
            <div class="col-6 col-lg-3">
                <div class="bestseller-card h-100 d-flex flex-column">
                    <div class="bestseller-img-box" style="background-color: <?php echo $randomColor; ?>;">
                        <?php if ($isBsSale): 
                            $bsDiscount = round((($item['old_price'] - $item['price']) / $item['old_price']) * 100);
                        ?>
                            <span class="badge bg-danger position-absolute top-0 start-0 m-3 z-2 px-2 py-1">-<?php echo $bsDiscount; ?>%</span>
                        <?php endif; ?>
                        
                        <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                        
                        <a href="index.php?controller=product&action=detail&id=<?php echo $item['id']; ?>">
                            <img src="<?php echo htmlspecialchars($imgBs); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        </a>
                        
                        <a href="index.php?controller=cart&action=add&id=<?php echo $item['id']; ?>" class="cart-btn-circle" title="Thêm vào giỏ">
                            <i class="fas fa-shopping-bag"></i>
                        </a>
                    </div>
                    <div class="pt-3 flex-grow-1 d-flex flex-column text-center text-md-start px-2">
                        <p class="product-brand-name text-uppercase text-truncate"><?php echo htmlspecialchars($item['category'] ?? ''); ?></p>
                        <a href="index.php?controller=product&action=detail&id=<?php echo $item['id']; ?>" class="product-title-link mb-2">
                            <?php echo htmlspecialchars($item['name']); ?>
                        </a>
                        <div class="text-warning small mb-2">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <div class="mt-auto d-flex align-items-center justify-content-center justify-content-md-start flex-wrap">
                            <span class="fw-bold fs-5" style="color: var(--brand-primary, #db2777);"><?php echo number_format($item['price']); ?>đ</span>
                            <?php if ($isBsSale): ?>
                                <span class="text-muted text-decoration-line-through small ms-2"><?php echo number_format($item['old_price']); ?>đ</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted text-center py-4">Chưa có sản phẩm bán chạy.</p>
        <?php endif; ?>
    </div>
    
    <div class="text-center mt-4 d-md-none">
        <a href="index.php?controller=product&action=index" class="btn btn-outline-dark rounded-pill px-4">Xem tất cả sản phẩm</a>
    </div>
</section>

<!-- Các Logo Thương Hiệu Nổi Bật -->
<div class="container border-bottom pb-5 mb-5 d-none d-md-block">
    <div class="d-flex justify-content-center align-items-center gap-5 flex-wrap opacity-50">
        <h3 class="fw-bold" style="font-family: serif; letter-spacing: 2px;">CHANEL</h3>
        <h3 class="fw-bold" style="font-family: serif; letter-spacing: 2px;">DIOR</h3>
        <h3 class="fw-bold" style="font-family: serif; letter-spacing: 2px;">M.A.C</h3>
        <h3 class="fw-bold" style="font-family: serif; letter-spacing: 2px;">L'OREAL</h3>
        <h3 class="fw-bold" style="font-family: serif; letter-spacing: 2px;">MAYBELLINE</h3>
    </div>
</div>
<?php endif; ?>


<!-- ========================================================== -->
<!-- 4. MAIN SHOP: DANH SÁCH SẢN PHẨM & BỘ LỌC TÌM KIẾM -->
<!-- ========================================================== -->
<section id="products-section" class="container py-4 <?php echo $isHomepage ? '' : 'mt-4'; ?>">
    <div class="row">
        
        <!-- CỘT TRÁI: BỘ LỌC SIDEBAR -->
        <div class="col-lg-3 mb-4">
            <button class="btn btn-outline-dark w-100 d-lg-none mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#filterSidebarCollapse">
                <i class="fas fa-filter me-2"></i> Hiện/Ẩn Bộ Lọc
            </button>
            
            <div class="filter-sidebar collapse d-lg-block" id="filterSidebarCollapse">
                <div class="d-flex align-items-center mb-4 border-bottom pb-3">
                    <i class="fas fa-sliders-h fs-5 me-2" style="color: var(--brand-primary, #db2777);"></i>
                    <h5 class="fw-bold mb-0 m-0" style="color: var(--brand-primary, #db2777);">Bộ Lọc Tìm Kiếm</h5>
                </div>

                <form action="index.php" method="GET" id="filterForm">
                    <input type="hidden" name="controller" value="product">
                    <input type="hidden" name="action" value="index">
                    
                    <?php if(!empty($_GET['keyword'])): ?>
                        <input type="hidden" name="keyword" value="<?php echo htmlspecialchars($_GET['keyword']); ?>">
                    <?php endif; ?>

                    <div class="filter-group">
                        <div class="filter-title" data-bs-toggle="collapse" data-bs-target="#filterPrice" aria-expanded="true">Giá sản phẩm</div>
                        <div class="collapse show" id="filterPrice">
                            <ul class="filter-list">
                                <li class="filter-item"><label><input type="checkbox" name="price[]" value="0-500000"> Dưới 500.000đ</label></li>
                                <li class="filter-item"><label><input type="checkbox" name="price[]" value="500000-1000000"> 500k - 1 Triệu</label></li>
                                <li class="filter-item"><label><input type="checkbox" name="price[]" value="1000000-2000000"> 1 Triệu - 2 Triệu</label></li>
                                <li class="filter-item"><label><input type="checkbox" name="price[]" value="2000000-0"> Trên 2 Triệu</label></li>
                            </ul>
                        </div>
                    </div>

                    <div class="filter-group">
                        <div class="filter-title" data-bs-toggle="collapse" data-bs-target="#filterCategory" aria-expanded="true">Loại sản phẩm</div>
                        <div class="collapse show" id="filterCategory">
                            <div class="filter-search-box"><i class="fas fa-search"></i><input type="text" class="searchInput" data-target="catList" placeholder="Tìm loại..."></div>
                            <ul class="filter-list" id="catList" style="max-height: 250px; overflow-y: auto;">
                                <?php 
                                if(isset($filterCategories) && isset($catCounts)) {
                                    $catIndex = 0;
                                    foreach($filterCategories as $cat): 
                                        $count = isset($catCounts[$cat]) ? $catCounts[$cat] : 0;
                                        $isHidden = $catIndex >= 5 ? 'hidden-item' : '';
                                    ?>
                                        <li class="filter-item <?php echo $isHidden; ?>"><label><input type="checkbox" name="category[]" value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?> <span class="text-muted ms-1">(<?php echo $count; ?>)</span></label></li>
                                    <?php $catIndex++; endforeach; 
                                } ?>
                            </ul>
                            <button type="button" class="btn-view-more" data-target="catList">Xem thêm</button>
                        </div>
                    </div>

                    <div class="filter-group">
                        <div class="filter-title" data-bs-toggle="collapse" data-bs-target="#filterBrand" aria-expanded="true">Thương hiệu</div>
                        <div class="collapse show" id="filterBrand">
                            <div class="filter-search-box"><i class="fas fa-search"></i><input type="text" class="searchInput" data-target="brandList" placeholder="Tìm thương hiệu..."></div>
                            <ul class="filter-list" id="brandList" style="max-height: 250px; overflow-y: auto;">
                                <?php 
                                if(isset($filterBrands) && isset($brandCounts)) {
                                    $brandIndex = 0;
                                    foreach($filterBrands as $brand): 
                                        $count = isset($brandCounts[$brand]) ? $brandCounts[$brand] : 0;
                                        $isHidden = $brandIndex >= 5 ? 'hidden-item' : '';
                                    ?>
                                        <li class="filter-item <?php echo $isHidden; ?>"><label><input type="checkbox" name="brand[]" value="<?php echo htmlspecialchars($brand); ?>"><?php echo htmlspecialchars($brand); ?> <span class="text-muted ms-1">(<?php echo $count; ?>)</span></label></li>
                                    <?php $brandIndex++; endforeach; 
                                } ?>
                            </ul>
                            <button type="button" class="btn-view-more" data-target="brandList">Xem thêm</button>
                        </div>
                    </div>

                    <button type="submit" class="btn w-100 mt-2 border-0 py-3 text-white fw-bold shadow-sm" style="border-radius: 8px; background-color: var(--brand-primary, #db2777);">Lọc kết quả</button>
                    
                    <?php if(!$isHomepage): ?>
                        <a href="index.php" class="btn w-100 mt-2 bg-light text-dark py-2 border">Xóa bộ lọc</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- CỘT PHẢI: LƯỚI SẢN PHẨM -->
        <div class="col-lg-9">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 border-bottom pb-3">
                <div class="mb-3 mb-md-0">
                    <h2 class="m-0 fw-bold" style="font-family: var(--font-heading, 'Playfair Display', serif); color: var(--brand-primary, #db2777);">
                        <?php echo isset($pageTitle) ? $pageTitle : 'Cửa Hàng Mỹ Phẩm'; ?>
                    </h2>
                    <?php if(isset($subTitle)): ?>
                        <p class="text-muted mb-0 mt-1"><?php echo $subTitle; ?> (Tìm thấy <?php echo count($products); ?> kết quả)</p>
                    <?php endif; ?>
                </div>
                
                <div class="d-flex align-items-center bg-light px-3 py-1 rounded-pill border">
                    <span class="text-muted small me-2 text-nowrap"><i class="fas fa-sort-amount-down me-1"></i> Sắp xếp:</span>
                    <select name="sort" form="filterForm" onchange="document.getElementById('filterForm').submit();" class="form-select form-select-sm bg-transparent border-0 shadow-none fw-medium" style="cursor: pointer;">
                        <option value="">Mặc định</option>
                        <option value="newest">Hàng mới về</option>
                        <option value="price_asc">Giá: Thấp đến Cao</option>
                        <option value="price_desc">Giá: Cao đến Thấp</option>
                    </select>
                </div>
            </div>

            <!-- Lưới Sản phẩm Thực tế -->
            <div class="row g-4">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $row): 
                        $imgUrl = !empty($row['image']) ? $row['image'] : 'https://via.placeholder.com/400x400?text=No+Image';
                        $isSale = (isset($row['old_price']) && $row['old_price'] > $row['price']);
                    ?>
                        <div class="col-md-4 col-6 d-flex">
                            <div class="product-card d-flex flex-column w-100">
                                <div class="product-img-container">
                                    <?php if ($isSale): 
                                        $discountPercent = round((($row['old_price'] - $row['price']) / $row['old_price']) * 100);
                                    ?>
                                        <span class="position-absolute top-0 start-0 m-3 z-3 px-2 py-1 bg-white border border-danger text-danger rounded fw-bold" style="font-size: 0.75rem;">-<?php echo $discountPercent; ?>%</span>
                                    <?php elseif (isset($currentFilter) && $currentFilter == 'new'): ?>
                                        <span class="position-absolute top-0 start-0 m-3 z-3 px-2 py-1 bg-white border border-success text-success rounded fw-bold" style="font-size: 0.75rem;">MỚI</span>
                                    <?php endif; ?>

                                    <a href="index.php?controller=product&action=detail&id=<?php echo $row['id']; ?>">
                                        <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" onerror="this.src='https://via.placeholder.com/400x400?text=No+Image'">
                                    </a>
                                    
                                    <div class="quick-view-overlay">
                                        <button type="button" class="btn-quick-view"
                                            data-id="<?php echo $row['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                            data-price="<?php echo number_format($row['price']); ?>"
                                            data-oldprice="<?php echo $isSale ? number_format($row['old_price']) : ''; ?>"
                                            data-img="<?php echo htmlspecialchars($imgUrl); ?>"
                                            data-cat="<?php echo htmlspecialchars($row['category'] ?? ''); ?>">
                                            Xem nhanh
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="product-info text-center mt-3 px-2 flex-grow-1 d-flex flex-column">
                                    <p class="product-desc mb-1 text-muted text-uppercase fw-medium" style="font-size: 0.75rem; letter-spacing: 1px;">
                                        <?php echo htmlspecialchars($row['category'] ?? ''); ?>
                                    </p>
                                    
                                    <h3 class="product-title-clamp">
                                        <a href="index.php?controller=product&action=detail&id=<?php echo $row['id']; ?>" class="text-decoration-none" style="color: inherit;">
                                            <?php echo htmlspecialchars($row['name']); ?>
                                        </a>
                                    </h3>
                                    
                                    <div class="text-warning mb-2" style="font-size: 0.75rem;">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                    </div>
                                    
                                    <div class="product-price mt-auto" style="font-size: 1.15rem; color: var(--brand-primary, #db2777); font-weight: 600;">
                                        <?php if ($isSale): ?>
                                            <span class="text-muted text-decoration-line-through me-2 fw-normal" style="font-size: 0.9rem;">
                                                <?php echo number_format($row['old_price']); ?>đ
                                            </span>
                                        <?php endif; ?>
                                        <?php echo number_format($row['price']); ?>đ
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5 bg-light rounded-4 border">
                        <i class="fas fa-search fa-3x text-muted mb-3 opacity-50"></i>
                        <h5 class="text-muted">Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</h5>
                        <a href="index.php" class="btn btn-outline-dark mt-3 rounded-pill">Khám phá tất cả sản phẩm</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($products) && count($products) >= 12): ?>
            <nav aria-label="Page navigation" class="mt-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled"><a class="page-link border-0 text-muted" href="#"><i class="fas fa-chevron-left"></i></a></li>
                    <li class="page-item active"><a class="page-link border-0" href="#" style="background-color: var(--brand-primary, #db2777); border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">1</a></li>
                    <li class="page-item"><a class="page-link border-0 text-dark" href="#">2</a></li>
                    <li class="page-item"><a class="page-link border-0 text-dark" href="#">3</a></li>
                    <li class="page-item"><a class="page-link border-0 text-dark" href="#"><i class="fas fa-chevron-right"></i></a></li>
                </ul>
            </nav>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- ========================================================== -->
<!-- MODAL POPUP XEM NHANH SẢN PHẨM -->
<!-- ========================================================== -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius: 16px;">
      <div class="modal-header border-0 pb-0 position-absolute top-0 end-0 z-3">
        <button type="button" class="btn-close bg-white p-2 shadow-sm m-2" style="border-radius: 50%;" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="row g-0">
            <div class="col-md-5 d-flex align-items-center justify-content-center p-4" style="background: var(--bg-card, #f8fafc);">
                <img id="qv-img" src="" class="img-fluid mix-blend-multiply" style="max-height: 400px; object-fit: contain;">
            </div>
            <div class="col-md-7 p-4 p-md-5 d-flex flex-column justify-content-center bg-white">
                <span id="qv-cat" class="text-muted text-uppercase mb-2 align-self-start fw-bold" style="letter-spacing: 1px; font-size: 0.8rem;">Category</span>
                <h4 id="qv-name" class="fw-bold mb-3" style="font-family: var(--font-heading, 'Playfair Display', serif); color: var(--text-dark, #1f2937); font-size: 1.8rem; line-height: 1.3;">Tên sản phẩm</h4>
                
                <div class="text-warning mb-3" style="font-size: 0.9rem;">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    <span class="text-muted ms-2">(128 đánh giá)</span>
                </div>

                <div class="d-flex align-items-end gap-3 mb-4 border-bottom pb-4">
                    <h2 id="qv-price" class="fw-bold mb-0" style="color: var(--brand-primary, #db2777); font-size: 1.8rem;">0đ</h2>
                    <span id="qv-oldprice" class="text-muted text-decoration-line-through mb-1" style="font-size: 1.1rem;"></span>
                </div>
                
                <p class="text-muted small mb-4 lh-lg"><i class="fas fa-check-circle text-success me-2"></i> Cam kết hàng chính hãng 100%<br><i class="fas fa-truck text-primary me-2"></i> Miễn phí giao hàng toàn quốc</p>

                <div class="d-flex gap-2 mt-auto">
                    <a id="qv-add-cart" href="#" class="btn flex-grow-1 text-white border-0 py-3 rounded-pill shadow-sm" style="background-color: var(--brand-primary, #db2777); font-weight: 600; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px; transition: transform 0.2s;">
                        <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ
                    </a>
                    <a id="qv-detail" href="#" class="btn btn-outline-dark py-3 px-4 rounded-pill" style="font-weight: 600; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">
                        Chi tiết
                    </a>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
// KỊCH BẢN JAVASCRIPT
$extraJS = <<<EOT
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Khởi tạo Swiper Banner
    if (document.querySelector('.hero-swiper')) {
        new Swiper(".hero-swiper", {
            slidesPerView: 1, loop: true, effect: "fade", fadeEffect: { crossFade: true },
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: ".swiper-pagination", clickable: true },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" }
        });
    }

    // 2. Modal Xem Nhanh
    const quickViewBtns = document.querySelectorAll('.btn-quick-view');
    if (quickViewBtns.length > 0) {
        const myModal = new bootstrap.Modal(document.getElementById('quickViewModal'));

        quickViewBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault(); e.stopPropagation();

                document.getElementById('qv-img').src = this.getAttribute('data-img');
                document.getElementById('qv-name').innerText = this.getAttribute('data-name');
                document.getElementById('qv-cat').innerText = this.getAttribute('data-cat');
                document.getElementById('qv-price').innerText = this.getAttribute('data-price') + 'đ';
                
                const oldPrice = this.getAttribute('data-oldprice');
                document.getElementById('qv-oldprice').innerText = oldPrice ? oldPrice + 'đ' : '';

                const pId = this.getAttribute('data-id');
                document.getElementById('qv-add-cart').href = 'index.php?controller=cart&action=add&id=' + pId;
                document.getElementById('qv-detail').href = 'index.php?controller=product&action=detail&id=' + pId;

                myModal.show();
            });
        });
    }

    // 3. Mở rộng/Thu gọn Bộ Lọc
    document.querySelectorAll('.btn-view-more').forEach(btn => {
        btn.addEventListener('click', function() {
            const hiddenItems = document.getElementById(this.getAttribute('data-target')).querySelectorAll('.hidden-item');
            if (this.innerText === 'Xem thêm') {
                hiddenItems.forEach(item => item.style.display = 'flex');
                this.innerText = 'Thu gọn';
            } else {
                hiddenItems.forEach(item => item.style.display = 'none');
                this.innerText = 'Xem thêm';
            }
        });
    });

    // 4. Tìm kiếm trong bộ lọc
    document.querySelectorAll('.searchInput').forEach(input => {
        input.addEventListener('keyup', function() {
            const filterText = this.value.toLowerCase().trim();
            const items = document.getElementById(this.getAttribute('data-target')).querySelectorAll('.filter-item');
            const btnMore = this.parentElement.parentElement.querySelector('.btn-view-more');
            if(btnMore) btnMore.style.display = filterText ? 'none' : 'block';

            items.forEach(item => {
                const text = item.querySelector('label').innerText.toLowerCase();
                const isHidden = item.classList.contains('hidden-item');
                if (text.includes(filterText)) item.style.display = 'flex';
                else item.style.display = 'none';
                if (!filterText && isHidden && btnMore && btnMore.innerText === 'Xem thêm') item.style.display = 'none';
            });
        });
    });

    // 5. Collapse Bộ lọc
    document.querySelectorAll('.filter-title').forEach(title => {
        title.addEventListener('click', function() { this.classList.toggle('collapsed'); });
    });

    // 6. Giữ trạng thái Checkbox
    const urlParams = new URLSearchParams(window.location.search);
    document.querySelectorAll('#filterForm input[type="checkbox"]').forEach(cb => {
        if (urlParams.getAll(cb.name).includes(cb.value)) cb.checked = true;
    });
    const sortSelect = document.querySelector('select[name="sort"]');
    if (sortSelect && urlParams.has('sort')) sortSelect.value = urlParams.get('sort');
});
</script>
EOT;
include 'views/layout/footer.php'; 
?>